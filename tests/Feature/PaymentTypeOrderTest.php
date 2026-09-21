<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\OrderService;
use App\Services\Pdf\InvoicePdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTypeOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
        CmsSetting::create(['key' => 'contact_phone', 'value' => '081234567890']);
        CmsSetting::create(['key' => 'dp_percentage', 'value' => '70']);
    }

    private function createSampleProduct(): Product
    {
        $supplier = Supplier::create([
            'name' => 'Mitra Bakery',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'daily_capacity' => 100,
        ]);

        $category = Category::create([
            'name' => 'Kue Basah',
            'slug' => 'kue-basah',
        ]);

        return Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Pastel Ayam Spesial',
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
        ]);
    }

    public function test_checkout_with_full_payment_sets_full_dp_amount_and_generates_full_whatsapp(): void
    {
        $product = $this->createSampleProduct();

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Ahmad Full',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Jl. Tebet Raya No. 5',
            'payment_type' => 'full',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'type' => 'satuan',
                ],
            ],
            'terms_agreed' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $order = Order::where('customer_name', 'Ahmad Full')->first();
        $this->assertNotNull($order);
        $this->assertEquals('full', $order->payment_type);
        $this->assertEquals(50000, (int) $order->total_amount);
        $this->assertEquals(50000, (int) $order->dp_amount);

        // WhatsApp message validation
        $orderService = app(OrderService::class);
        $waMessage = $orderService->generateWhatsAppMessage($order);
        $this->assertStringContainsString('PEMBAYARAN: Bayar Penuh (100%) - Rp 50.000', $waMessage);
        $this->assertStringContainsString('Sisa bayar: Rp 0 (Lunas)', $waMessage);
        $this->assertStringContainsString("- 10x Pastel Ayam Spesial: Rp 50.000\n\nTOTAL TAGIHAN: Rp 50.000", $waMessage);
        $this->assertStringNotContainsString("  Subtotal:", $waMessage);
        $this->assertStringContainsString("Pesanan Kue Satuan\n", $waMessage);
        $this->assertStringNotContainsString("[2] Pesanan Kue Satuan", $waMessage);
        $this->assertStringContainsString(route('tracking.index', ['order_number' => $order->order_number]), $waMessage);
        $this->assertStringContainsString(route('pdf.invoice', $order->order_number), $waMessage);
    }

    public function test_checkout_with_dp_sets_calculated_percentage_and_generates_dp_whatsapp(): void
    {
        $product = $this->createSampleProduct();

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Budi DP',
            'customer_phone' => '081234567891',
            'pickup_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Jl. Radio Dalam No. 12',
            'payment_type' => 'dp',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'type' => 'satuan',
                ],
            ],
            'terms_agreed' => true,
        ]);

        $response->assertStatus(200);

        $order = Order::where('customer_name', 'Budi DP')->first();
        $this->assertNotNull($order);
        $this->assertEquals('dp', $order->payment_type);
        $this->assertEquals(50000, (int) $order->total_amount);
        $this->assertEquals(35000, (int) $order->dp_amount); // 70% of 50.000

        // WhatsApp message validation
        $orderService = app(OrderService::class);
        $waMessage = $orderService->generateWhatsAppMessage($order);
        $this->assertStringContainsString('DP (70%): Rp 35.000', $waMessage);
        $this->assertStringContainsString('Sisa bayar: Rp 15.000 (dibayar saat pengambilan)', $waMessage);
    }

    public function test_checkout_defaults_to_dp_when_payment_type_is_omitted(): void
    {
        $product = $this->createSampleProduct();

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Citra Default',
            'customer_phone' => '081234567892',
            'pickup_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Jl. Senopati No. 8',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'type' => 'satuan',
                ],
            ],
            'terms_agreed' => true,
        ]);

        $response->assertStatus(200);

        $order = Order::where('customer_name', 'Citra Default')->first();
        $this->assertNotNull($order);
        $this->assertEquals('dp', $order->payment_type);
        $this->assertEquals(35000, (int) $order->dp_amount);
    }

    public function test_invoice_renders_correctly_for_full_payment(): void
    {
        $product = $this->createSampleProduct();

        $order = Order::create([
            'order_number' => 'PK-FULL1001',
            'customer_name' => 'Doni Full',
            'customer_phone' => '081234567893',
            'pickup_date' => now()->addDays(2),
            'location' => 'Jakarta Barat',
            'total_amount' => 100000,
            'dp_amount' => 100000,
            'payment_type' => 'full',
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 20,
            'price_at_order' => 5000,
            'type' => 'satuan',
        ]);

        $pdfService = app(InvoicePdfService::class);
        $data = $pdfService->prepareInvoiceData($order);

        $this->assertEquals('full', $data['payment_type']);
        $this->assertEquals(0, $data['remaining_amount']);
        $this->assertEquals(100000, $data['dp_amount']);

        $html = view('Pdf.invoice', compact('data'))->render();
        $this->assertStringContainsString('Pembayaran Penuh (100%)', $html);
        $this->assertStringContainsString('Rp 0 (LUNAS)', $html);
    }

    public function test_invoice_renders_correctly_for_dp_payment(): void
    {
        $product = $this->createSampleProduct();

        $order = Order::create([
            'order_number' => 'PK-DP700001',
            'customer_name' => 'Eka DP',
            'customer_phone' => '081234567894',
            'pickup_date' => now()->addDays(2),
            'location' => 'Jakarta Selatan',
            'total_amount' => 100000,
            'dp_amount' => 70000,
            'payment_type' => 'dp',
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 20,
            'price_at_order' => 5000,
            'type' => 'satuan',
        ]);

        $pdfService = app(InvoicePdfService::class);
        $data = $pdfService->prepareInvoiceData($order);

        $this->assertEquals('dp', $data['payment_type']);
        $this->assertEquals(30000, $data['remaining_amount']);
        $this->assertEquals(70000, $data['dp_amount']);

        $html = view('Pdf.invoice', compact('data'))->render();
        $this->assertStringContainsString('DP (70%)', $html);
        $this->assertStringContainsString('Rp 30.000', $html);
    }
}
