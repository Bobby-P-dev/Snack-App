<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of orders with search & filters
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');
        $perPage = 10;

        $query = Order::with(['items.product.supplier', 'items.product.category']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(order_number) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(customer_name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(customer_phone) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->whereDate('pickup_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('pickup_date', '<=', $endDate);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return Inertia::render('Admin/Order/Index', [
            'orders' => OrderResource::collection($orders->items())->resolve(request()),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
            ],
            'filters' => [
                'search' => $search,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate,
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
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,diterima,diproses,dikemas,dikirim,selesai']);

        $newStatus = $request->input('status');

        $this->orderRepository->update($order->id, ['status' => $newStatus]);

        $statusLabel = $this->statusLabel($newStatus);
        return redirect()->back()->with('success', 'Pesanan berhasil diubah ke "' . $statusLabel . '"');
    }

    /**
     * Get human-readable status label
     */
    private function statusLabel($status): string
    {
        $labels = [
            'pending' => 'Pending',
            'diterima' => 'Diterima',
            'diproses' => 'Diproses',
            'dikemas' => 'Dikemas',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
        ];
        return $labels[$status] ?? $status;
    }

    /**
     * Get orders by status filter
     */
    public function getByStatus($status)
    {
        $orders = $this->orderRepository->getByStatus($status);

        return response()->json([
            'data' => OrderResource::collection($orders),
        ]);
    }
}
