<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create suppliers
        $suppliers = [
            [
                'name' => 'Kue Lezat Bakery',
                'phone' => '081234567890',
                'address' => 'Jl. Raya Kemang No. 123, Jakarta Selatan',
                'daily_capacity' => 100,
            ],
            [
                'name' => 'Donat Enak Express',
                'phone' => '082345678901',
                'address' => 'Jl. Gatot Subroto No. 456, Jakarta Pusat',
                'daily_capacity' => 150,
            ],
            [
                'name' => 'Snack Tradisional Jaya',
                'phone' => '083456789012',
                'address' => 'Jl. Sudirman No. 789, Jakarta Barat',
                'daily_capacity' => 200,
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::firstOrCreate(
                ['name' => $supplierData['name']],
                $supplierData
            );
        }

        // Create categories
        $categories = [
            ['name' => 'Kue Coklat', 'slug' => 'kue-coklat'],
            ['name' => 'Kue Vanila', 'slug' => 'kue-vanila'],
            ['name' => 'Donat', 'slug' => 'donat'],
            ['name' => 'Cookies', 'slug' => 'cookies'],
            ['name' => 'Brownies', 'slug' => 'brownies'],
            ['name' => 'Croissant', 'slug' => 'croissant'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create products
        $suppliers = Supplier::all();
        $categories = Category::all();

        $products = [
            [
                'supplier_id' => $suppliers[0]->id,
                'category_id' => $categories[0]->id,
                'name' => 'Kue Coklat Premium',
                'base_price' => 15000,
                'sell_price' => 25000,
                'is_active' => true,
            ],
            [
                'supplier_id' => $suppliers[0]->id,
                'category_id' => $categories[1]->id,
                'name' => 'Kue Vanila Lembut',
                'base_price' => 12000,
                'sell_price' => 20000,
                'is_active' => true,
            ],
            [
                'supplier_id' => $suppliers[1]->id,
                'category_id' => $categories[2]->id,
                'name' => 'Donat Glazur Gula',
                'base_price' => 2000,
                'sell_price' => 5000,
                'is_active' => true,
            ],
            [
                'supplier_id' => $suppliers[1]->id,
                'category_id' => $categories[2]->id,
                'name' => 'Donat Coklat Filling',
                'base_price' => 2500,
                'sell_price' => 6000,
                'is_active' => true,
            ],
            [
                'supplier_id' => $suppliers[2]->id,
                'category_id' => $categories[3]->id,
                'name' => 'Cookies Keju',
                'base_price' => 1500,
                'sell_price' => 4000,
                'is_active' => true,
            ],
            [
                'supplier_id' => $suppliers[2]->id,
                'category_id' => $categories[4]->id,
                'name' => 'Brownies Coklat Gelap',
                'base_price' => 10000,
                'sell_price' => 18000,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@snackbox.test'],
            [
                'name' => 'Admin Snack Box',
                'password' => bcrypt('password'),
            ]
        );

        if (!$adminUser->hasRole('super_admin')) {
            $adminUser->assignRole('super_admin');
        }

        // Create supplier user
        $supplierUser = User::firstOrCreate(
            ['email' => 'supplier@snackbox.test'],
            [
                'name' => 'Supplier User',
                'password' => bcrypt('password'),
            ]
        );

        if (!$supplierUser->hasRole('supplier')) {
            $supplierUser->assignRole('supplier');
        }

        // Create default snack box packages
        $packages = [
            [
                'name' => 'Snack Box 3 Kue',
                'slug' => 'snack-box-3-kue',
                'capacity' => 3,
                'box_price' => 2500,
                'description' => 'Paket hemat untuk acara santai, arisan, atau snack rapat singkat.',
                'is_active' => true,
            ],
            [
                'name' => 'Snack Box 4 Kue',
                'slug' => 'snack-box-4-kue',
                'capacity' => 4,
                'box_price' => 2500,
                'description' => 'Paling populer! Kombinasi pas 2 kue manis, 1 asin gurih, dan 1 puding/roti.',
                'is_active' => true,
            ],
            [
                'name' => 'Snack Box 5 Kue',
                'slug' => 'snack-box-5-kue',
                'capacity' => 5,
                'box_price' => 3000,
                'description' => 'Paket komplit premium untuk acara resmi, pernikahan, atau seminar besar.',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $packageData) {
            \App\Models\SnackBoxPackage::firstOrCreate(
                ['slug' => $packageData['slug']],
                $packageData
            );
        }
    }
}

