<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderProcessingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
        CmsSetting::create(['key' => 'contact_phone', 'value' => '081234567890']);
        CmsSetting::create(['key' => 'dp_percentage', 'value' => '50']);
    }

    public function test_can_generate_secure_random_order_number(): void
    {
        $orderService = app(OrderService::class);
        $generated = [];

        for ($i = 0; $i < 50; $i++) {
            $orderNumber = $orderService->generateOrderNumber();

            // Must match PK-XXXXXXXX (8 uppercase chars from safe alphabet)
            $this->assertMatchesRegularExpression('/^PK-[23456789ABCDEFGHJKMNPQRSTUVWXYZ]{8}$/', $orderNumber);

            // Must not contain confusing characters (0, O, 1, I, L)
            $this->assertDoesNotMatchRegularExpression('/[0O1IL]/', $orderNumber);

            $this->assertArrayNotHasKey($orderNumber, $generated);
            $generated[$orderNumber] = true;
        }
    }

    public function test_checkout_creates_order_with_transaction_and_recalculated_prices(): void
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

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Lemper Ayam Spesial',
            'base_price' => 2500,
            'sell_price' => 4000,
            'is_active' => true,
        ]);

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Jl. Kemang Raya No. 10, Jakarta Selatan',
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
        $response->assertJsonStructure([
            'success',
            'order' => [
                'order_number',
                'customer_name',
                'total_amount',
                'dp_amount',
                'status',
            ],
            'whatsappUrl',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals('Budi Santoso', $order->customer_name);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals(40000, (int) $order->total_amount);
        $this->assertEquals(20000, (int) $order->dp_amount);
        $this->assertStringStartsWith('PK-', $order->order_number);

        // Check order item
        $this->assertCount(1, $order->items);
        $item = $order->items->first();
        $this->assertEquals($product->id, $item->product_id);
        $this->assertEquals(10, $item->quantity);
        $this->assertEquals(4000, (int) $item->price_at_order);
    }

    public function test_admin_can_update_order_status_through_valid_lifecycle(): void
    {
        $orderService = app(OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => 'Siti Nurhaliza',
            'customer_phone' => '081298765432',
            'pickup_date' => now()->addDay(),
            'location' => 'Bandung',
        ], []);

        $this->assertEquals('pending', $order->status);

        // Confirm order -> diterima
        $order = $orderService->confirmOrder($order->id);
        $this->assertEquals('diterima', $order->status);

        // Progress to diproses
        $order = $orderService->updateOrderStatus($order->id, 'diproses');
        $this->assertEquals('diproses', $order->status);

        // Progress to dikemas
        $order = $orderService->updateOrderStatus($order->id, 'dikemas');
        $this->assertEquals('dikemas', $order->status);

        // Progress to dikirim
        $order = $orderService->updateOrderStatus($order->id, 'dikirim');
        $this->assertEquals('dikirim', $order->status);

        // Complete order -> selesai
        $order = $orderService->completeOrder($order->id);
        $this->assertEquals('selesai', $order->status);
    }

    public function test_whatsapp_message_is_clean_and_contains_invoice_link(): void
    {
        $supplier = Supplier::create([
            'name' => 'Dapur Kue',
            'phone' => '0811111111',
            'address' => 'Jakarta',
            'daily_capacity' => 50,
        ]);

        $category = Category::create([
            'name' => 'Pastry',
            'slug' => 'pastry',
        ]);

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Croissant Almond',
            'base_price' => 15000,
            'sell_price' => 25000,
            'is_active' => true,
        ]);

        $orderService = app(OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => 'Ahmad Dani',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDays(2),
            'location' => 'Jakarta Pusat',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 2,
                'price_at_order' => 25000,
                'type' => 'satuan',
            ],
        ]);

        $message = $orderService->generateWhatsAppMessage($order);

        $this->assertStringNotContainsString('tes aja', $message);
        $this->assertStringContainsString('INVOICE PESANAN - Padu Kue', $message);
        $this->assertStringContainsString($order->order_number, $message);
        $this->assertStringContainsString('Croissant Almond', $message);
        $this->assertStringContainsString('TOTAL TAGIHAN: Rp 50.000', $message);
        $this->assertStringContainsString('DP (50%): Rp 25.000', $message);
        $this->assertStringContainsString(route('pdf.invoice', $order->order_number), $message);
        $this->assertStringContainsString(route('tracking.index', ['order_number' => $order->order_number]), $message);
    }

    public function test_generate_whatsapp_message_uses_custom_cms_template(): void
    {
        $orderService = app(OrderService::class);
        $supplier = Supplier::create([
            'name' => 'Bakery Artisanal',
            'contact_person' => 'Chef Budi',
            'phone' => '081234567890',
            'email' => 'artisanal@bakery.com',
            'address' => 'Jakarta Barat',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Pastry',
            'slug' => 'pastry',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'name' => 'Danish Cokelat',
            'slug' => 'danish-cokelat',
            'base_price' => 12000,
            'sell_price' => 20000,
            'status' => 'active',
        ]);

        $order = $orderService->createOrder([
            'customer_name' => 'Siti Nurhaliza',
            'customer_phone' => '081399887766',
            'pickup_date' => now()->addDays(1),
            'location' => 'Bandung',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 3,
                'price_at_order' => 20000,
                'type' => 'satuan',
            ],
        ]);

        \App\Models\CmsSetting::create([
            'key' => 'wa_checkout_template',
            'value' => "HALO {company_name}! Customer: {customer_name} ({order_number})\nTotal: Rp {total_amount}\nUnduh: {invoice_url}",
            'type' => 'textarea',
        ]);

        \App\Models\CmsSetting::updateOrCreate(
            ['key' => 'company_name'],
            ['value' => 'Padu Kue Nusantara', 'type' => 'text']
        );

        $message = $orderService->generateWhatsAppMessage($order);

        $this->assertStringContainsString('HALO Padu Kue Nusantara!', $message);
        $this->assertStringContainsString('Customer: Siti Nurhaliza', $message);
        $this->assertStringContainsString($order->order_number, $message);
        $this->assertStringContainsString('Total: Rp 60.000', $message);
        $this->assertStringContainsString(route('pdf.invoice', $order->order_number), $message);
    }

    public function test_generate_whatsapp_message_safely_appends_invoice_url_if_omitted(): void
    {
        $orderService = app(OrderService::class);
        $supplier = Supplier::create([
            'name' => 'Bakery Artisanal 2',
            'contact_person' => 'Chef Budi',
            'phone' => '081234567891',
            'email' => 'artisanal2@bakery.com',
            'address' => 'Jakarta Barat',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Pastry',
            'slug' => 'pastry-2',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'name' => 'Eclair Vanilla',
            'slug' => 'eclair-vanilla',
            'base_price' => 15000,
            'sell_price' => 25000,
            'status' => 'active',
        ]);

        $order = $orderService->createOrder([
            'customer_name' => 'Rian Hidayat',
            'customer_phone' => '081399887711',
            'pickup_date' => now()->addDays(1),
            'location' => 'Bekasi',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 1,
                'price_at_order' => 25000,
                'type' => 'satuan',
            ],
        ]);

        // Custom template that omitted {invoice_url}
        \App\Models\CmsSetting::create([
            'key' => 'wa_checkout_template',
            'value' => "STRUK KUSTOM PESANAN {order_number} untuk {customer_name}",
            'type' => 'textarea',
        ]);

        $message = $orderService->generateWhatsAppMessage($order);

        $this->assertStringContainsString("STRUK KUSTOM PESANAN {$order->order_number} untuk Rian Hidayat", $message);
        // Ensure invoice url and tracking url are still appended
        $this->assertStringContainsString(route('pdf.invoice', $order->order_number), $message);
        $this->assertStringContainsString(route('tracking.index', ['order_number' => $order->order_number]), $message);
    }

    public function test_admin_order_show_json_endpoint_returns_order_resource_with_payment_type(): void
    {
        $user = \App\Models\User::factory()->create();
        $orderService = app(OrderService::class);

        $order = $orderService->createOrder([
            'customer_name' => 'Dewi Persik',
            'customer_phone' => '081298765432',
            'pickup_date' => now()->addDays(3),
            'location' => 'Jakarta',
            'payment_type' => 'dp',
        ], []);

        $response = $this->actingAs($user)
            ->getJson("/admin/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => 'Dewi Persik',
                'payment_type' => 'dp',
            ],
        ]);
    }

    public function test_checkout_persists_notes_and_exposes_it_in_order_resource(): void
    {
        $supplier = Supplier::create([
            'name' => 'Mitra Bakery Notes',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'daily_capacity' => 100,
        ]);

        $category = Category::create([
            'name' => 'Kue Basah',
            'slug' => 'kue-basah-notes',
        ]);

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Lemper Ayam Notes',
            'base_price' => 2500,
            'sell_price' => 4000,
            'is_active' => true,
        ]);

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Budi Catatan',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Jl. Kebon Jeruk No. 2',
            'notes' => 'Tolong pisahkan kotak kue coklat dengan lemper ayam.',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'type' => 'satuan',
                ],
            ],
            'payment_type' => 'full',
            'terms_agreed' => true,
        ]);

        $response->assertStatus(200);

        $order = Order::where('customer_name', 'Budi Catatan')->first();
        $this->assertNotNull($order);
        $this->assertEquals('Tolong pisahkan kotak kue coklat dengan lemper ayam.', $order->notes);

        $user = \App\Models\User::factory()->create();
        $adminResponse = $this->actingAs($user)->getJson("/admin/orders/{$order->id}");

        $adminResponse->assertStatus(200);
        $adminResponse->assertJson([
            'success' => true,
            'data' => [
                'order_number' => $order->order_number,
                'notes' => 'Tolong pisahkan kotak kue coklat dengan lemper ayam.',
            ],
        ]);

        $items = $adminResponse->json('data.items');
        $this->assertIsArray($items);
        $this->assertCount(1, $items);
        $this->assertEquals('Lemper Ayam Notes', $items[0]['product']['name']);
        $this->assertEquals(10, $items[0]['quantity']);
    }

    public function test_admin_order_show_returns_inertia_response_for_inertia_navigation(): void
    {
        $user = \App\Models\User::factory()->create();
        $orderService = app(\App\Services\OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => 'Inertia Tester',
            'customer_phone' => '081211223344',
            'pickup_date' => now()->addDays(2),
            'location' => 'Bandung',
            'payment_type' => 'full',
            'notes' => 'Catatan tes inertia',
        ], []);

        // Verify standard Inertia page request
        $response = $this->actingAs($user)
            ->get("/admin/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Admin/Order/Show')
                ->has('order')
                ->where('order.order_number', $order->order_number)
                ->where('order.notes', 'Catatan tes inertia')
        );

        // Verify explicit json format query returns JSON response
        $jsonQueryResponse = $this->actingAs($user)
            ->get("/admin/orders/{$order->id}?format=json");

        $jsonQueryResponse->assertStatus(200);
        $jsonQueryResponse->assertJson([
            'success' => true,
            'data' => [
                'order_number' => $order->order_number,
                'notes' => 'Catatan tes inertia',
            ],
        ]);
    }

    public function test_order_creation_sets_package_type_correctly(): void
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

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Pastel Ayam',
            'base_price' => 2000,
            'sell_price' => 4000,
            'is_active' => true,
        ]);

        $orderService = app(OrderService::class);

        // 1. Only Satuan
        $orderSatuan = $orderService->createOrder([
            'customer_name' => 'Satuan User',
            'customer_phone' => '081234567891',
            'pickup_date' => now()->addDays(1),
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'price_at_order' => 4000,
                'type' => 'satuan',
                'box_group_id' => null,
            ],
        ]);
        $this->assertEquals('satuan', $orderSatuan->fresh()->package_type);

        // 2. Only Snack Box
        $orderBox = $orderService->createOrder([
            'customer_name' => 'Box User',
            'customer_phone' => '081234567892',
            'pickup_date' => now()->addDays(1),
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 20,
                'price_at_order' => 4000,
                'type' => 'kustom_box',
                'box_group_id' => 1,
            ],
        ]);
        $this->assertEquals('snack_box', $orderBox->fresh()->package_type);

        // 3. Campuran (Box + Satuan)
        $orderMix = $orderService->createOrder([
            'customer_name' => 'Mix User',
            'customer_phone' => '081234567893',
            'pickup_date' => now()->addDays(1),
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 20,
                'price_at_order' => 4000,
                'type' => 'kustom_box',
                'box_group_id' => 1,
            ],
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'price_at_order' => 4000,
                'type' => 'satuan',
                'box_group_id' => null,
            ],
        ]);
        $this->assertEquals('campuran', $orderMix->fresh()->package_type);
    }

    public function test_admin_can_filter_orders_by_package_type(): void
    {
        $user = \App\Models\User::factory()->create();
        $orderService = app(OrderService::class);

        $orderBox = $orderService->createOrder([
            'customer_name' => 'User Box Only',
            'customer_phone' => '081234567894',
            'pickup_date' => now()->addDays(1),
            'package_type' => 'snack_box',
        ], []);

        $orderSatuan = $orderService->createOrder([
            'customer_name' => 'User Satuan Only',
            'customer_phone' => '081234567895',
            'pickup_date' => now()->addDays(1),
            'package_type' => 'satuan',
        ], []);

        // Filter for snack_box
        $response = $this->actingAs($user)->get('/admin/orders?package_type=snack_box');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Admin/Order/Index')
                ->where('filters.package_type', 'snack_box')
                ->has('orders', 1)
                ->where('orders.0.order_number', $orderBox->order_number)
        );

        // Filter for satuan
        $response = $this->actingAs($user)->get('/admin/orders?package_type=satuan');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Admin/Order/Index')
                ->where('filters.package_type', 'satuan')
                ->has('orders', 1)
                ->where('orders.0.order_number', $orderSatuan->order_number)
        );
    }
}

