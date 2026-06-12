<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of orders
     */
    public function index()
    {
        $orders = $this->orderRepository->paginate(15);

        return Inertia::render('Admin/Order/Index', [
            'orders' => OrderResource::collection($orders->items()),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
            ],
            'title' => 'Daftar Pesanan',
        ]);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        return Inertia::render('Admin/Order/Show', [
            'order' => new OrderResource($order),
            'title' => 'Pesanan: ' . $order->order_number,
        ]);
    }

    /**
     * Confirm order status
     */
    public function confirm(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pesanan pending yang dapat dikonfirmasi');
        }

        $this->orderRepository->update($order->id, ['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi');
    }

    /**
     * Complete order status
     */
    public function complete(Order $order)
    {
        if ($order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Hanya pesanan confirmed yang dapat diselesaikan');
        }

        $this->orderRepository->update($order->id, ['status' => 'completed']);

        return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan');
    }

    /**
     * Get orders by status (API endpoint)
     */
    public function getByStatus($status)
    {
        $this->authorize('view_orders');

        $orders = $this->orderRepository->getByStatus($status);

        return response()->json([
            'data' => OrderResource::collection($orders),
        ]);
    }
}
