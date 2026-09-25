<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartMixedItemsTest extends TestCase
{
    use RefreshDatabase;

    protected $supplier;
    protected $category;
    protected $productA;
    protected $productB;

    protected function setUp(): void
    {
        parent::setUp();

        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
        CmsSetting::create(['key' => 'min_order_box', 'value' => '10']);
        CmsSetting::create(['key' => 'min_order_satuan', 'value' => '10']);
        CmsSetting::create(['key' => 'dp_percentage', 'value' => '70']);

        $this->supplier = Supplier::create([
            'name' => 'Mitra Padu Kue',
            'phone' => '081234567890',
            'address' => 'Jakarta Barat',
            'daily_capacity' => 100,
        ]);

        $this->category = Category::create([
            'name' => 'Kue Tradisional',
            'slug' => 'kue-tradisional',
        ]);

        $this->productA = Product::create([
            'supplier_id' => $this->supplier->id,
            'category_id' => $this->category->id,
            'name' => 'Lemper Ayam Special',
            'base_price' => 2000,
            'sell_price' => 3500,
            'is_active' => true,
        ]);

        $this->productB = Product::create([
            'supplier_id' => $this->supplier->id,
            'category_id' => $this->category->id,
            'name' => 'Pastel Rogout Sapi',
            'base_price' => 2500,
            'sell_price' => 4000,
            'is_active' => true,
        ]);
    }

    public function test_cart_can_contain_same_product_as_both_satuan_and_snackbox(): void
    {
        // Add Product A as satuan (12 pcs)
        $this->postJson('/cart/add', [
            'product_id' => $this->productA->id,
            'quantity' => 12,
            'type' => 'satuan',
        ])->assertStatus(200);

        // Add Product A as kustom_box (15 boxes)
        $this->postJson('/cart/add', [
            'product_id' => $this->productA->id,
            'quantity' => 15,
            'type' => 'kustom_box',
            'box_group_id' => 1001,
        ])->assertStatus(200);

        // Verify /cart/items API returns both with proper types
        $response = $this->getJson('/cart/items');
        $response->assertStatus(200);
        $response->assertJson([
            'count' => 2,
        ]);

        $items = $response->json('items');
        $this->assertCount(2, $items);
        $this->assertEquals('satuan', $items[0]['type']);
        $this->assertEquals(12, $items[0]['qty']);
        $this->assertEquals('kustom_box', $items[1]['type']);
        $this->assertEquals(15, $items[1]['qty']);
        $this->assertEquals(1001, $items[1]['box_group_id']);
    }

    public function test_cart_item_removal_targets_specific_type_and_box_group(): void
    {
        // Add Product A as satuan and as kustom_box
        session()->put('cart', [
            [
                'product_id' => $this->productA->id,
                'quantity' => 12,
                'type' => 'satuan',
                'box_group_id' => null,
            ],
            [
                'product_id' => $this->productA->id,
                'quantity' => 15,
                'type' => 'kustom_box',
                'box_group_id' => 1001,
            ],
        ]);

        // Remove only the satuan item
        $response = $this->postJson('/cart/remove', [
            'product_id' => $this->productA->id,
            'type' => 'satuan',
        ]);

        $response->assertStatus(200);

        $cart = session()->get('cart');
        $this->assertCount(1, $cart);
        $this->assertEquals('kustom_box', $cart[0]['type']);
        $this->assertEquals(1001, $cart[0]['box_group_id']);
        $this->assertEquals(15, $cart[0]['quantity']);
    }

    public function test_cart_remove_box_group_removes_all_items_in_that_box(): void
    {
        // Add 2 items to box group 2002, and 1 satuan item
        session()->put('cart', [
            [
                'product_id' => $this->productA->id,
                'quantity' => 10,
                'type' => 'kustom_box',
                'box_group_id' => 2002,
            ],
            [
                'product_id' => $this->productB->id,
                'quantity' => 10,
                'type' => 'kustom_box',
                'box_group_id' => 2002,
            ],
            [
                'product_id' => $this->productA->id,
                'quantity' => 10,
                'type' => 'satuan',
                'box_group_id' => null,
            ],
        ]);

        $response = $this->postJson('/cart/remove-box-group', [
            'box_group_id' => 2002,
        ]);

        $response->assertStatus(200);

        $cart = session()->get('cart');
        $this->assertCount(1, $cart);
        $this->assertEquals('satuan', $cart[0]['type']);
        $this->assertEquals($this->productA->id, $cart[0]['product_id']);
    }

    public function test_cart_update_quantity_targets_specific_type_and_box_group(): void
    {
        session()->put('cart', [
            [
                'product_id' => $this->productA->id,
                'quantity' => 12,
                'type' => 'satuan',
                'box_group_id' => null,
            ],
            [
                'product_id' => $this->productA->id,
                'quantity' => 15,
                'type' => 'kustom_box',
                'box_group_id' => 3003,
            ],
        ]);

        // Update quantity of kustom_box to 20
        $response = $this->postJson('/cart/update-quantity', [
            'product_id' => $this->productA->id,
            'quantity' => 20,
            'type' => 'kustom_box',
            'box_group_id' => 3003,
        ]);

        $response->assertStatus(200);

        $cart = session()->get('cart');
        $satuan = collect($cart)->firstWhere('type', 'satuan');
        $box = collect($cart)->firstWhere('type', 'kustom_box');

        $this->assertEquals(12, $satuan['quantity']);
        $this->assertEquals(20, $box['quantity']);
    }

    public function test_checkout_show_renders_mixed_cart_with_product_resources(): void
    {
        session()->put('cart', [
            [
                'product_id' => $this->productA->id,
                'quantity' => 12,
                'type' => 'satuan',
                'box_group_id' => null,
            ],
            [
                'product_id' => $this->productB->id,
                'quantity' => 10,
                'type' => 'kustom_box',
                'box_group_id' => 4004,
            ],
        ]);

        $response = $this->get('/checkout');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Checkout/Show')
            ->has('cart', 2)
            ->where('cart.0.product.name', 'Lemper Ayam Special')
            ->where('cart.0.type', 'satuan')
            ->where('cart.1.product.name', 'Pastel Rogout Sapi')
            ->where('cart.1.type', 'kustom_box')
            ->where('cart.1.box_group_id', 4004)
        );
    }
}
