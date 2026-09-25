<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Product;
use App\Models\SnackBoxPackage;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
    }

    public function test_home_page_returns_featured_products_and_snack_box_packages(): void
    {
        $supplier = Supplier::create([
            'name' => 'Supplier Berkah',
            'phone' => '081234567890',
            'address' => 'Jakarta',
            'daily_capacity' => 100,
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Kue Basah',
            'slug' => 'kue-basah',
        ]);

        for ($i = 1; $i <= 8; $i++) {
            Product::create([
                'supplier_id' => $supplier->id,
                'category_id' => $category->id,
                'name' => "Kue Enak $i",
                'description' => "Deskripsi kue $i",
                'base_price' => 2000,
                'sell_price' => 3000,
                'is_active' => true,
            ]);
        }

        SnackBoxPackage::create([
            'name' => 'Snack Box 3 Kue',
            'slug' => 'snack-box-3-kue',
            'capacity' => 3,
            'box_price' => 2500,
            'description' => 'Paket 3 kue hemat',
            'is_active' => true,
        ]);

        SnackBoxPackage::create([
            'name' => 'Snack Box 4 Kue',
            'slug' => 'snack-box-4-kue',
            'capacity' => 4,
            'box_price' => 2500,
            'description' => 'Paket 4 kue populer',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Welcome')
            ->has('products', 6)
            ->has('snackBoxPackages', 2)
            ->has('categories')
        );
    }
}
