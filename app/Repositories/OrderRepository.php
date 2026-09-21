<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository implements RepositoryInterface
{
    protected $model = Order::class;

    /**
     * Get all orders with relations
     */
    public function all()
    {
        return $this->model::with(['items.product.supplier', 'items.product.category'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get order by ID with relations
     */
    public function find($id)
    {
        return $this->model::with(['items.product.supplier', 'items.product.category'])
            ->findOrFail($id);
    }

    /**
     * Get order by order number
     */
    public function findByOrderNumber($orderNumber)
    {
        return $this->model::with(['items.product.supplier', 'items.product.category'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
    }

    /**
     * Get orders by status
     */
    public function getByStatus($status)
    {
        return $this->model::with(['items.product.supplier', 'items.product.category'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending orders
     */
    public function getPending()
    {
        return $this->getByStatus('pending');
    }

    /**
     * Get confirmed / diterima orders
     */
    public function getConfirmed()
    {
        return $this->getByStatus('diterima');
    }

    /**
     * Get completed / selesai orders
     */
    public function getCompleted()
    {
        return $this->getByStatus('selesai');
    }

    /**
     * Create new order
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Update order
     */
    public function update($id, array $data)
    {
        $order = $this->find($id);
        $order->update($data);
        return $order;
    }

    /**
     * Delete order
     */
    public function delete($id)
    {
        $order = $this->find($id);
        $order->delete();
        return $order;
    }

    /**
     * Get paginated orders
     */
    public function paginate($perPage = 15)
    {
        return $this->model::with(['items.product.supplier', 'items.product.category'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
