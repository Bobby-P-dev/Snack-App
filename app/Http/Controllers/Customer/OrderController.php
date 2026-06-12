<?php

namespace App\Http\Controllers\Customer;

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
     * Display customer's orders
     */
    public function index()
    {
        $orders = Order::where('customer_phone', auth()->user()->email) // In real app, store customer phone with order
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Customer/Order/Index', [
            'orders' => OrderResource::collection($orders->items()),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
            ],
            'title' => 'Pesanan Saya',
        ]);
    }

    /**
     * Display specific order detail
     */
    public function show(Order $order)
    {
        // Check if user has permission to view this order
        // In real app, verify customer phone matches

        return Inertia::render('Customer/Order/Show', [
            'order' => new OrderResource($order),
            'title' => 'Pesanan: ' . $order->order_number,
        ]);
    }

    /**
     * Get order by order number (public API for checking status)
     */
    public function checkStatus($orderNumber)
    {
        $order = $this->orderRepository->findByOrderNumber($orderNumber);

        return response()->json([
            'data' => new OrderResource($order),
        ]);
    }
}
