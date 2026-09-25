<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminOrderEditTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Supplier $supplier;
    private Category $category;
    private Product $productA;
    private Product $productB;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
        CmsSetting::create(['key' => 'company_address', 'value' => 'Jl. Boulevard Raya No. 88, Bekasi']);
        CmsSetting::create(['key' => 'dp_percentage', 'value' => '70']);

        $this->admin = User::factory()->create();

        $this->supplier = Supplier::create([
            'name' => 'Mitra Bakery Berkah',
            'phone' => '08123456789',
            'address' => 'Jakarta Timur',
            'daily_capacity' => 150,
        ]);

        $this->category = Category::create([
            'name' => 'Kue Basah',
            'slug' => 'kue-basah',
        ]);

        $this->productA = Product::create([
            'supplier_id' => $this->supplier->id,
            'category_id' => $this->category->id,
            'name' => 'Lemper Ayam Premium',
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
        ]);

        $this->productB = Product::create([
            'supplier_id' => $this->supplier->id,
            'category_id' => $this->category->id,
            'name' => 'Pastel Sayur Telur',
            'base_price' => 2500,
            'sell_price' => 4500,
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'order_number' => 'PK-TESTEDIT',
            'customer_name' => 'Pelanggan Asli',
            'customer_phone' => '081299887766',
            'pickup_date' => now()->addDays(2),
            'location' => 'Ambil di Tempat (Padu Kue: Jl. Boulevard Raya No. 88, Bekasi)',
            'notes' => 'Catatan awal',
            'package_type' => 'satuan',
            'payment_type' => 'dp',
            'dp_amount' => 35000,
            'total_amount' => 50000,
            'status' => 'pending',
        ]);

        $this->order->items()->create([
            'product_id' => $this->productA->id,
            'quantity' => 10,
            'price_at_order' => 5000,
            'type' => 'satuan',
        ]);
    }

    public function test_guest_cannot_access_order_edit_or_update(): void
    {
        $responseEdit = $this->get(route('admin.orders.edit', $this->order->id));
        $responseEdit->assertRedirect(route('login'));

        $responseUpdate = $this->put(route('admin.orders.update', $this->order->id), [
            'customer_name' => 'Hacker',
        ]);
        $responseUpdate->assertRedirect(route('login'));
    }

    public function test_admin_can_view_order_edit_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.edit', $this->order->id));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Order/Edit')
            ->has('order')
            ->where('order.id', $this->order->id)
            ->where('order.order_number', 'PK-TESTEDIT')
            ->has('availableProducts')
            ->has('cmsSettings')
            ->has('statuses')
        );
    }

    public function test_admin_can_update_customer_and_logistics_details(): void
    {
        $existingItem = $this->order->items->first();

        $payload = [
            'customer_name' => 'Budi Santoso (Diperbarui)',
            'customer_phone' => '081211112222',
            'pickup_date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'location' => 'Gedung Pertemuan Graha Surya Lt. 3, Jakarta',
            'notes' => 'Tolong sertakan kartu ucapan pernikahan',
            'status' => 'diterima',
            'payment_type' => 'dp',
            'dp_amount' => 35000,
            'total_amount' => 50000,
            'items' => [
                [
                    'id' => $existingItem->id,
                    'product_id' => $this->productA->id,
                    'quantity' => 10,
                    'price_at_order' => 5000,
                    'type' => 'satuan',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order->id), $payload);

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->order->refresh();
        $this->assertEquals('Budi Santoso (Diperbarui)', $this->order->customer_name);
        $this->assertEquals('081211112222', $this->order->customer_phone);
        $this->assertEquals('Gedung Pertemuan Graha Surya Lt. 3, Jakarta', $this->order->location);
        $this->assertEquals('Tolong sertakan kartu ucapan pernikahan', $this->order->notes);
        $this->assertEquals('diterima', $this->order->status);
    }

    public function test_admin_can_modify_item_quantities_and_prices(): void
    {
        $existingItem = $this->order->items->first();

        $payload = [
            'customer_name' => $this->order->customer_name,
            'customer_phone' => $this->order->customer_phone,
            'pickup_date' => $this->order->pickup_date->format('Y-m-d H:i:s'),
            'location' => $this->order->location,
            'notes' => $this->order->notes,
            'status' => 'diproses',
            'payment_type' => 'full',
            'dp_amount' => 90000,
            'total_amount' => 90000,
            'items' => [
                [
                    'id' => $existingItem->id,
                    'product_id' => $this->productA->id,
                    'quantity' => 20, // increased from 10 to 20
                    'price_at_order' => 4500, // discounted price
                    'type' => 'satuan',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order->id), $payload);

        $response->assertRedirect(route('admin.orders.index'));

        $this->order->refresh();
        $this->assertEquals('full', $this->order->payment_type);
        $this->assertEquals(90000, (float)$this->order->total_amount);
        $this->assertEquals(90000, (float)$this->order->dp_amount);

        $existingItem->refresh();
        $this->assertEquals(20, $existingItem->quantity);
        $this->assertEquals(4500, (float)$existingItem->price_at_order);
    }

    public function test_admin_can_add_new_product_and_remove_existing(): void
    {
        $existingItem = $this->order->items->first();

        // Admin replaces productA with productB and sets new quantity
        $payload = [
            'customer_name' => $this->order->customer_name,
            'customer_phone' => $this->order->customer_phone,
            'pickup_date' => $this->order->pickup_date->format('Y-m-d H:i:s'),
            'location' => $this->order->location,
            'notes' => 'Ganti kue pastel',
            'status' => 'dikemas',
            'payment_type' => 'dp',
            'dp_amount' => 47250,
            'total_amount' => 67500,
            'items' => [
                [
                    'id' => null, // newly added item
                    'product_id' => $this->productB->id,
                    'quantity' => 15,
                    'price_at_order' => 4500,
                    'type' => 'satuan',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order->id), $payload);

        $response->assertRedirect(route('admin.orders.index'));

        $this->order->refresh();
        // Item A should be deleted, item B should be present
        $this->assertDatabaseMissing('order_items', ['id' => $existingItem->id]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $this->order->id,
            'product_id' => $this->productB->id,
            'quantity' => 15,
        ]);
        $this->assertEquals(1, $this->order->items()->count());
    }

    public function test_validation_requires_at_least_one_item_and_valid_data(): void
    {
        $payload = [
            'customer_name' => '', // invalid empty
            'customer_phone' => '', // invalid empty
            'pickup_date' => '',
            'location' => '',
            'status' => 'invalid_status',
            'payment_type' => 'invalid_type',
            'dp_amount' => -100,
            'total_amount' => -500,
            'items' => [], // invalid empty items
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order->id), $payload);

        $response->assertSessionHasErrors([
            'customer_name',
            'customer_phone',
            'pickup_date',
            'location',
            'status',
            'payment_type',
            'dp_amount',
            'total_amount',
            'items',
        ]);
    }
}
