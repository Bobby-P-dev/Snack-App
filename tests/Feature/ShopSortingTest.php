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
}
