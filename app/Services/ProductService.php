<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $productRepository;
    protected $imageManager;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Create product with image upload
     */
    public function createProduct(array $data, $imageFile = null): \App\Models\Product
    {
        if ($imageFile) {
            $data['image_url'] = $this->uploadAndCompressImage($imageFile);
        }

        return $this->productRepository->create($data);
    }

    /**
     * Update product with optional image upload
     */
    public function updateProduct($productId, array $data, $imageFile = null): \App\Models\Product
    {
        if ($imageFile) {
            $product = $this->productRepository->find($productId);

            // Delete old image if exists
            if ($product->image_url) {
                Storage::disk('s3')->delete($product->image_url);
            }

            $data['image_url'] = $this->uploadAndCompressImage($imageFile);
        }

        return $this->productRepository->update($productId, $data);
    }

    /**
     * Upload and compress image to S3
     */
    public function uploadAndCompressImage($imageFile): string
    {
        // Read image
        $image = $this->imageManager->read($imageFile->getRealPath());

        // Resize to max 800x800 while maintaining aspect ratio
        $image->resize(width: 800, height: 800);

        // Encode to WebP with 75% quality
        $encoded = $image->encodeUsingFormat(Format::WEBP, quality: 75);

        // Generate unique filename
        $filename = 'products/' . uniqid() . '.webp';

        // Upload to S3
        Storage::disk('s3')->put($filename, (string) $encoded);

        return $filename;
    }

    /**
     * Delete product
     */
    public function deleteProduct($productId): \App\Models\Product
    {
        $product = $this->productRepository->find($productId);

        // Delete image if exists
        if ($product->image_url) {
            Storage::disk('s3')->delete($product->image_url);
        }

        return $this->productRepository->delete($productId);
    }

    /**
     * Toggle product active status
     */
    public function toggleProductStatus($productId): \App\Models\Product
    {
        $product = $this->productRepository->find($productId);
        $product->update(['is_active' => !$product->is_active]);

        $this->productRepository->flushCache();

        return $product;
    }

    /**
     * Get all products for customer view (active only)
     */
    public function getProductsForCustomer()
    {
        return $this->productRepository->all();
    }

    /**
     * Get products by category for customer
     */
    public function getProductsByCategory($categoryId)
    {
        return $this->productRepository->getByCategory($categoryId);
    }

    /**
     * Get all products for admin (including inactive)
     */
    public function getProductsForAdmin()
    {
        return $this->productRepository->allIncludingInactive();
    }
}
