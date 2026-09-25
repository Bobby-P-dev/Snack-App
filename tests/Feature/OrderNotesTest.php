<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderNotesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create base product
        $supplier = Supplier::create([
            'name' => 'Dapur Uji',
            'phone' => '081234567890',
            'address' => 'Jl. Uji No. 1',
            'daily_capacity' => 500,
        ]);

        $category = Category::create([
            'name' => 'Kue Basah',
            'slug' => 'kue-basah',
            'is_active' => true,
        ]);

        Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Kue Lapis Legit',
            'base_price' => 15000,
            'sell_price' => 25000,
            'is_active' => true,
        ]);
    }

    public function test_checkout_saves_notes_properly_to_database()
    {
        $product = Product::first();

        $payload = [
            'customer_name' => 'Budi Testing Notes',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'location' => 'Gedung Pertemuan Lt. 3',
            'notes' => 'Tolong pasang pita warna gold dan kartu ucapan Happy Birthday!',
            'payment_type' => 'dp',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'type' => 'satuan',
                ],
            ],
            'terms_agreed' => true,
        ];

        $response = $this->postJson(route('checkout.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi Testing Notes',
            'notes' => 'Tolong pasang pita warna gold dan kartu ucapan Happy Birthday!',
        ]);

        $order = Order::where('customer_name', 'Budi Testing Notes')->first();
        $this->assertNotNull($order);
        $this->assertEquals('Tolong pasang pita warna gold dan kartu ucapan Happy Birthday!', $order->notes);
    }
}
