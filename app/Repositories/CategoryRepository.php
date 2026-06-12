<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements RepositoryInterface
{
    protected $model = Category::class;
    protected $cacheTag = 'categories';
    protected $cacheDuration = 3600; // 1 hour

    /**
     * Get all categories with relations (cached)
     */
    public function all()
    {
        return $this->model::with('products')
            ->get();
    }

    /**
     * Get category by ID with relations
     */
    public function find($id)
    {
        return $this->model::with('products')
            ->findOrFail($id);
    }

    /**
     * Get category by slug
     */
    public function findBySlug($slug)
    {
        return $this->model::with('products')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Create new category
     */
    public function create(array $data)
    {
        $category = $this->model::create($data);
        $this->flushCache();
        return $category;
    }

    /**
     * Update category
     */
    public function update($id, array $data)
    {
        $category = $this->find($id);
        $category->update($data);
        $this->flushCache();
        return $category;
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        $category = $this->find($id);
        $category->delete();
        $this->flushCache();
        return $category;
    }

    /**
     * Get paginated categories
     */
    public function paginate($perPage = 15)
    {
        return $this->model::with('products')
            ->paginate($perPage);
    }

    /**
     * Flush category cache
     */
    public function flushCache()
    {
        // Caching disabled - will enable when Redis is configured
    }
}
