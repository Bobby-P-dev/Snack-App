<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelPdf\Facades\Pdf;
use Tests\TestCase;

class InvoicePdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
        CmsSetting::create(['key' => 'contact_phone', 'value' => '081234567890']);
        CmsSetting::create(['key' => 'dp_percentage', 'value' => '50']);
    }

    private function createSampleOrder(): Order
    {
        $supplier = Supplier::create([
            'name' => 'Mitra Bakery',
            'phone' => '081111111111',
            'address' => 'Jl. Bakery No. 1',
            'daily_capacity' => 100,
        ]);

        $category = Category::create([
            'name' => 'Kue Basah',
            'slug' => 'kue-basah',
        ]);

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Lemper Ayam Special',
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'PK-AB2345CD',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDays(2),
            'location' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'total_amount' => 50000,
            'dp_amount' => 25000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'price_at_order' => 5000,
            'type' => 'satuan',
        ]);

        return $order;
    }

    public function test_can_download_invoice_pdf_by_order_number(): void
    {
        Pdf::fake();

        $order = $this->createSampleOrder();

        $response = $this->get(route('pdf.invoice', $order->order_number));

        $response->assertStatus(200);

        Pdf::assertRespondedWithPdf(function ($pdf) {
            return $pdf->viewName === 'Pdf.invoice'
                && str_contains($pdf->downloadName ?? '', 'invoice-PK-AB2345CD.pdf');
        });
    }

    public function test_cannot_access_invoice_with_invalid_order_number(): void
    {
        $response = $this->get('/pdf/invoice/PK-NONEXISTENT');

        $response->assertStatus(404);
    }

    public function test_invoice_views_render_safely_even_when_logo_missing(): void
    {
        $order = $this->createSampleOrder();

        $dpPercentage = 50;
        $dpAmount = ($order->total_amount * $dpPercentage) / 100;

        $data = [
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'location' => $order->location,
            'total_amount' => $order->total_amount,
            'dp_amount' => $dpAmount,
            'dp_percentage' => $dpPercentage,
            'status' => $order->status,
            'all_items' => $order->items()->with('product')->get(),
        ];

        // Ensure main invoice view compiles and renders
        $invoiceHtml = view('Pdf.invoice', compact('data'))->render();
        $this->assertStringContainsString('PK-AB2345CD', $invoiceHtml);
        $this->assertStringContainsString('Budi Santoso', $invoiceHtml);
        $this->assertStringContainsString('Lemper Ayam Special', $invoiceHtml);

        // Ensure header view renders without throwing missing file exceptions
        $headerHtml = view('Pdf.headerInvoice')->render();
        $this->assertNotEmpty($headerHtml);

        // Ensure footer view renders
        $footerHtml = view('Pdf.footerInvoice')->render();
        $this->assertNotEmpty($footerHtml);
    }
}
