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

class TrackingSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CmsSetting::create(['key' => 'company_name', 'value' => 'Padu Kue']);
    }

    public function test_public_tracking_masks_customer_phone(): void
    {
        $orderService = app(OrderService::class);
        $order = $orderService->createOrder([
            'customer_name' => 'Dewi Sartika',
            'customer_phone' => '081234567890',
            'pickup_date' => now()->addDay(),
            'location' => 'Jl. Merdeka No. 45, Jakarta',
        ], []);

        $response = $this->get('/tracking?order_number=' . $order->order_number);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Tracking/Index')
            ->has('order', fn ($page) => $page
                ->where('order_number', $order->order_number)
                ->where('customer_phone', '0812****7890')
                ->where('customer_name', 'Dewi Sartika')
                ->where('download_invoice_url', route('pdf.invoice', $order->order_number))
                ->etc()
            )
        );
    }

    public function test_product_resource_hides_supplier_and_base_price_from_guests(): void
    {
        $supplier = Supplier::create([
            'name' => 'Secret Artisan Bakery',
            'phone' => '0899999999',
            'address' => 'Secret Location',
            'daily_capacity' => 100,
        ]);

        $category = Category::create([
            'name' => 'Traditional',
            'slug' => 'traditional',
        ]);

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Kue Lapis Legit',
            'base_price' => 30000,
            'sell_price' => 50000,
            'is_active' => true,
        ]);

        $response = $this->get('/shop/' . $product->id);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Customer/Shop/Show')
            ->has('product', fn ($page) => $page
                ->where('name', 'Kue Lapis Legit')
                ->where('sell_price', '50000.00')
                ->missing('base_price')
                ->missing('supplier')
                ->etc()
            )
        );
    }
}
