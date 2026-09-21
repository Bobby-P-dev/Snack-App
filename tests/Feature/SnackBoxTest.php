<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Product;
use App\Models\SnackBoxPackage;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SnackBoxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
    }

    public function test_can_browse_snack_box_packages(): void
    {
        $package = SnackBoxPackage::create([
            'name' => 'Snack Box 4 Kue',
            'slug' => 'snack-box-4-kue',
            'capacity' => 4,
            'box_price' => 2500,
            'description' => 'Paket populer 4 kue',
            'is_active' => true,
        ]);

        $response = $this->get('/snack-box');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/SnackBox/Index')
            ->has('packages', 1)
        );
    }

    public function test_can_open_snack_box_builder(): void
    {
        $supplier = Supplier::create([
            'name' => 'Kue Mantap',
            'phone' => '0811111111',
            'address' => 'Jakarta',
            'daily_capacity' => 50,
        ]);

        $category = Category::create([
            'name' => 'Kue Manis',
            'slug' => 'kue-manis',
        ]);

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Bolu Gulung Mini',
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        $package = SnackBoxPackage::create([
            'name' => 'Snack Box 4 Kue',
            'slug' => 'snack-box-4-kue',
            'capacity' => 4,
            'box_price' => 2500,
            'is_active' => true,
        ]);

        $response = $this->get('/snack-box/' . $package->slug);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/SnackBox/Builder')
            ->where('package.capacity', 4)
            ->has('products', 1)
            ->has('categories', 1)
        );
    }

    public function test_validation_rejects_box_with_invalid_capacity(): void
    {
        $package = SnackBoxPackage::create([
            'name' => 'Snack Box 4 Kue',
            'slug' => 'snack-box-4-kue',
            'capacity' => 4,
            'box_price' => 2500,
            'is_active' => true,
        ]);

        $supplier = Supplier::create([
            'name' => 'Mitra Bakery',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'daily_capacity' => 100,
        ]);

        $category = Category::create([
            'name' => 'Snack',
            'slug' => 'snack',
        ]);

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Risoles Rogout',
            'base_price' => 2000,
            'sell_price' => 3500,
            'is_active' => true,
        ]);

        // Attempting to send only 2 items for a 4-capacity box
        $response = $this->postJson('/snack-box/add-to-cart', [
            'package_id' => $package->id,
            'box_quantity' => 10,
            'selected_items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_can_add_assembled_box_to_cart_with_exact_capacity(): void
    {
        $package = SnackBoxPackage::create([
            'name' => 'Snack Box 3 Kue',
            'slug' => 'snack-box-3-kue',
            'capacity' => 3,
            'box_price' => 2500,
            'is_active' => true,
        ]);

        $supplier = Supplier::create([
            'name' => 'Mitra Bakery',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'daily_capacity' => 100,
        ]);

        $category = Category::create([
            'name' => 'Snack',
            'slug' => 'snack',
        ]);

        $product1 = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Lemper Bakar',
            'base_price' => 2500,
            'sell_price' => 4000,
            'is_active' => true,
        ]);

        $product2 = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Pastel Ayam',
            'base_price' => 2500,
            'sell_price' => 4500,
            'is_active' => true,
        ]);

        // Selected 2x Lemper + 1x Pastel = 3 items (matches capacity 3)
        $response = $this->postJson('/snack-box/add-to-cart', [
            'package_id' => $package->id,
            'box_quantity' => 25, // 25 boxes
            'selected_items' => [
                ['product_id' => $product1->id, 'quantity' => 2],
                ['product_id' => $product2->id, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['success', 'cartCount', 'items', 'message']);
        $this->assertCount(2, $response->json('items'));

        // Verify session cart has the items with kustom_box type and box_group_id
        $cart = session()->get('cart', []);
        $this->assertCount(2, $cart);
        $this->assertEquals('kustom_box', $cart[0]['type']);
        $this->assertEquals(50, $cart[0]['quantity']); // 2 * 25 boxes
        $this->assertEquals(25, $cart[1]['quantity']); // 1 * 25 boxes
        $this->assertNotNull($cart[0]['box_group_id']);
        $this->assertEquals($cart[0]['box_group_id'], $cart[1]['box_group_id']);

        // Test /cart/items returns the items properly
        $cartResponse = $this->getJson('/cart/items');
        $cartResponse->assertStatus(200);
        $cartResponse->assertJsonStructure(['items', 'count']);
        $this->assertEquals(2, $cartResponse->json('count'));
    }

    public function test_custom_box_whatsapp_message_calculates_correct_box_count_price_per_box_and_subtotal(): void
    {
        $supplier = Supplier::create([
            'name' => 'Mitra Bakery',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'daily_capacity' => 100,
        ]);

        $category = Category::create([
            'name' => 'Snack',
            'slug' => 'snack-wa',
        ]);

        $p1 = Product::create(['supplier_id' => $supplier->id, 'category_id' => $category->id, 'name' => 'Kue Coklat', 'base_price' => 15000, 'sell_price' => 25000, 'is_active' => true]);
        $p2 = Product::create(['supplier_id' => $supplier->id, 'category_id' => $category->id, 'name' => 'Kue Vanila', 'base_price' => 12000, 'sell_price' => 20000, 'is_active' => true]);
        $p3 = Product::create(['supplier_id' => $supplier->id, 'category_id' => $category->id, 'name' => 'Donat Gula', 'base_price' => 3000, 'sell_price' => 5000, 'is_active' => true]);

        $boxGroupId = 123456789;
        $orderService = app(\App\Services\OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDays(2),
            'location' => 'Jakarta',
            'payment_type' => 'full',
        ], [
            ['product_id' => $p1->id, 'quantity' => 20, 'price_at_order' => 25000, 'type' => 'kustom_box', 'box_group_id' => $boxGroupId],
            ['product_id' => $p2->id, 'quantity' => 20, 'price_at_order' => 20000, 'type' => 'kustom_box', 'box_group_id' => $boxGroupId],
            ['product_id' => $p3->id, 'quantity' => 20, 'price_at_order' => 5000, 'type' => 'kustom_box', 'box_group_id' => $boxGroupId],
        ]);

        $this->assertEquals(1000000, (int) $order->total_amount);

        $waMessage = $orderService->generateWhatsAppMessage($order);

        $this->assertStringContainsString('- Jumlah: 20 Box', $waMessage);
        $this->assertStringNotContainsString('- Jumlah: 60 Box', $waMessage);
        $this->assertStringContainsString('- Harga per Box: Rp 50.000', $waMessage);
        $this->assertStringContainsString('- Subtotal: Rp 1.000.000', $waMessage);
        $this->assertStringContainsString('TOTAL TAGIHAN: Rp 1.000.000', $waMessage);
    }
}
