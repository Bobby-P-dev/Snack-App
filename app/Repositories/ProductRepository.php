<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements RepositoryInterface
{
    protected $model = Product::class;
    protected $cacheTag = 'products';
    protected $cacheDuration = 3600; // 1 hour

    /**
     * Get all active products with relations (cached for customers)
     */
    public function all()
    {
        return $this->model::with(['supplier', 'category'])
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get product by ID with relations
     */
    public function find($id)
    {
        return $this->model::with(['supplier', 'category'])
            ->findOrFail($id);
    }

    /**
     * Get all products including inactive (for admin)
     */
    public function allIncludingInactive()
    {
        return $this->model::with(['supplier', 'category'])
            ->get();
    }

    /**
     * Get products by supplier
     */
    public function getBySupplier($supplierId)
    {
        return $this->model::with(['supplier', 'category'])
            ->where('supplier_id', $supplierId)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get products by category
     */
    public function getByCategory($categoryId)
    {
        return $this->model::with(['supplier', 'category'])
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Create new product
     */
    public function create(array $data)
    {
        $product = $this->model::create($data);
        $this->flushCache();
        return $product;
    }

    /**
     * Update product
     */
    public function update($id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        $this->flushCache();
        return $product;
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        $product = $this->find($id);
        $product->delete();
        $this->flushCache();
        return $product;
    }

    /**
     * Get paginated products
     */
    public function paginate($perPage = 15)
    {
        return $this->model::with(['supplier', 'category'])
            ->paginate($perPage);
    }

    /**
     * Flush product cache
     */
    public function flushCache()
    {
        // Caching disabled - will enable when Redis is configured
    }
}
