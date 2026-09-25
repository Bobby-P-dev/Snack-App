<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopSortingTest extends TestCase
{
    use RefreshDatabase;

    protected $category;
    protected $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);

        $this->category = Category::create([
            'name' => 'Kue Basah',
            'slug' => 'kue-basah',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'Dapur Berkah',
            'email' => 'berkah@example.com',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'is_active' => true,
        ]);
    }

    public function test_can_sort_products_by_price_ascending(): void
    {
        Product::create([
            'name' => 'Kue Mahal',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 10000,
            'sell_price' => 15000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kue Murah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 3000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?sort=price_asc');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->where('filters.sort', 'price_asc')
            ->where('products.0.name', 'Kue Murah')
            ->where('products.1.name', 'Kue Mahal')
        );
    }

    public function test_can_sort_products_by_price_descending(): void
    {
        Product::create([
            'name' => 'Kue Murah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 3000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kue Mahal',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 10000,
            'sell_price' => 15000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?sort=price_desc');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->where('filters.sort', 'price_desc')
            ->where('products.0.name', 'Kue Mahal')
            ->where('products.1.name', 'Kue Murah')
        );
    }

    public function test_can_sort_products_by_name_alphabetically(): void
    {
        Product::create([
            'name' => 'Pastel Ayam',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Bolu Kukus Mekar',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 4000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?sort=name_asc');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->where('filters.sort', 'name_asc')
            ->where('products.0.name', 'Bolu Kukus Mekar')
            ->where('products.1.name', 'Pastel Ayam')
        );
    }

    public function test_can_filter_products_by_category(): void
    {
        $cat2 = Category::create([
            'name' => 'Kue Kering',
            'slug' => 'kue-kering',
        ]);

        Product::create([
            'name' => 'Kue Nastar',
            'category_id' => $cat2->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 15000,
            'sell_price' => 25000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Lemper Bakar',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2500,
            'sell_price' => 4500,
            'is_active' => true,
        ]);

        $response = $this->get("/shop?category_id={$cat2->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->has('products', 1)
            ->where('products.0.name', 'Kue Nastar')
        );
    }

    public function test_can_filter_products_by_search_query(): void
    {
        Product::create([
            'name' => 'Risoles Rogout Ayam',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 3000,
            'sell_price' => 6000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kue Sus Vanilla',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2500,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?search=Risoles');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->has('products', 1)
            ->where('products.0.name', 'Risoles Rogout Ayam')
        );
    }

    public function test_can_filter_products_by_price_range_under_10k(): void
    {
        Product::create([
            'name' => 'Pastel Murah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 4500,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kue Mahal',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 15000,
            'sell_price' => 20000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?price_range=under_10k');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->has('products', 1)
            ->where('products.0.name', 'Pastel Murah')
        );
    }

    public function test_can_filter_products_by_price_range_10k_to_25k(): void
    {
        Product::create([
            'name' => 'Pastel Murah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 4500,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kue Sedang',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 10000,
            'sell_price' => 15000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Hampers Spesial',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 25000,
            'sell_price' => 45000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?price_range=10k_25k');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->has('products', 1)
            ->where('products.0.name', 'Kue Sedang')
        );
    }

    public function test_can_filter_products_by_price_range_above_25k(): void
    {
        Product::create([
            'name' => 'Kue Murah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 4500,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Hampers Mewah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 30000,
            'sell_price' => 50000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?price_range=above_25k');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->has('products', 1)
            ->where('products.0.name', 'Hampers Mewah')
        );
    }

    public function test_can_filter_products_by_custom_min_and_max_price(): void
    {
        Product::create([
            'name' => 'Kue 5k',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kue 12k',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 8000,
            'sell_price' => 12000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kue 25k',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 15000,
            'sell_price' => 25000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop?price_range=custom&min_price=6000&max_price=15000');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->has('products', 1)
            ->where('products.0.name', 'Kue 12k')
        );
    }

    public function test_shop_page_is_dedicated_to_kue_satuan_without_snackbox_filter(): void
    {
        Product::create([
            'name' => 'Lumpia Basah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Index')
            ->where('title', 'Kue Satuan')
            ->missing('filters.type')
            ->has('products', 1)
            ->where('products.0.name', 'Lumpia Basah')
        );
    }
}


