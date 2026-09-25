<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Supplier $supplier;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('s3');

        $this->admin = User::factory()->create();

        $this->supplier = Supplier::create([
            'name' => 'Bakery Sejahtera',
            'phone' => '08123456789',
            'address' => 'Jakarta Barat',
            'daily_capacity' => 200,
        ]);

        $this->category = Category::create([
            'name' => 'Kue Tradisional',
            'slug' => 'kue-tradisional',
        ]);
    }

    public function test_admin_can_create_product_with_image_upload_to_s3(): void
    {
        $file = UploadedFile::fake()->image('lemper.png', 1000, 1000);

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name' => 'Lemper Spesial Ayam',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 3000,
            'sell_price' => 5000,
            'is_active' => true,
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Produk berhasil ditambahkan');

        $product = Product::where('name', 'Lemper Spesial Ayam')->first();
        $this->assertNotNull($product);

        $rawImage = $product->getRawOriginal('image_url');
        $this->assertNotNull($rawImage);
        $this->assertStringStartsWith('products/', $rawImage);
        $this->assertStringEndsWith('.webp', $rawImage);

        // Verify storage on S3 disk
        Storage::disk('s3')->assertExists($rawImage);

        // Verify image_url accessor
        $this->assertStringContainsString($rawImage, $product->image_url);
    }

    public function test_admin_can_update_product_and_replace_image_in_s3(): void
    {
        $oldFile = UploadedFile::fake()->image('old-cake.jpg', 500, 500);

        $this->actingAs($this->admin)->post('/admin/products', [
            'name' => 'Kue Lapis',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 4000,
            'sell_price' => 7000,
            'is_active' => true,
            'image' => $oldFile,
        ]);

        $product = Product::where('name', 'Kue Lapis')->first();
        $oldImagePath = $product->getRawOriginal('image_url');
        Storage::disk('s3')->assertExists($oldImagePath);

        $newFile = UploadedFile::fake()->image('new-cake.png', 800, 800);

        $updateResponse = $this->actingAs($this->admin)->put("/admin/products/{$product->id}", [
            'name' => 'Kue Lapis Legit',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 4500,
            'sell_price' => 8000,
            'is_active' => true,
            'image' => $newFile,
        ]);

        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success', 'Produk berhasil diperbarui');

        $product->refresh();
        $newImagePath = $product->getRawOriginal('image_url');

        $this->assertNotEquals($oldImagePath, $newImagePath);
        Storage::disk('s3')->assertMissing($oldImagePath);
        Storage::disk('s3')->assertExists($newImagePath);
    }

    public function test_deleting_product_removes_image_from_s3(): void
    {
        $file = UploadedFile::fake()->image('pastel.jpg', 400, 400);

        $this->actingAs($this->admin)->post('/admin/products', [
            'name' => 'Pastel Renyah',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 3500,
            'is_active' => true,
            'image' => $file,
        ]);

        $product = Product::where('name', 'Pastel Renyah')->first();
        $rawImage = $product->getRawOriginal('image_url');
        Storage::disk('s3')->assertExists($rawImage);

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/products/{$product->id}");
        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success', 'Produk berhasil dihapus');

        $this->assertNull(Product::find($product->id));
        Storage::disk('s3')->assertMissing($rawImage);
    }

    public function test_product_creation_rejects_non_image_files(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name' => 'Produk File Ilegal',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'base_price' => 2000,
            'sell_price' => 3500,
            'is_active' => true,
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['image']);
        $this->assertNull(Product::where('name', 'Produk File Ilegal')->first());
    }
}
