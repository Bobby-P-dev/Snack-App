<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository implements RepositoryInterface
{
    protected $model = Supplier::class;

    /**
     * Get all suppliers with relations
     */
    public function all()
    {
        return $this->model::with('products')
            ->get();
    }

    /**
     * Get supplier by ID with relations
     */
    public function find($id)
    {
        return $this->model::with('products')
            ->findOrFail($id);
    }

    /**
     * Create new supplier
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Update supplier
     */
    public function update($id, array $data)
    {
        $supplier = $this->find($id);
        $supplier->update($data);
        return $supplier;
    }

    /**
     * Delete supplier
     */
    public function delete($id)
    {
        $supplier = $this->find($id);
        $supplier->delete();
        return $supplier;
    }

    /**
     * Get paginated suppliers
     */
    public function paginate($perPage = 15)
    {
        return $this->model::with('products')
            ->paginate($perPage);
    }
}
