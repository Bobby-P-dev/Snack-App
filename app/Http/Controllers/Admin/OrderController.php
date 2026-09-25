<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $orderService;

    public function __construct(OrderRepository $orderRepository, OrderService $orderService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of orders with search & filters
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $packageType = $request->get('package_type', '');
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

        if ($packageType) {
            $query->where('package_type', $packageType);
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
                'package_type' => $packageType,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'title' => 'Daftar Pesanan',
        ]);
    }

    /**
     * Display the specified order (supports JSON fetch and Inertia page)
     */
    public function show(Request $request, Order $order)
    {
        $order->load(['items.product.supplier', 'items.product.category']);

        $resource = (new OrderResource($order))->resolve(request());

        // When navigating via Inertia (<Link> or router.visit), ALWAYS return Inertia page
        if ($request->header('X-Inertia')) {
            return Inertia::render('Admin/Order/Show', [
                'order' => $resource,
                'title' => 'Pesanan: ' . $order->order_number,
            ]);
        }

        // Return plain JSON only for explicit API/AJAX requests (e.g. modal quick fetch or getJson)
        if ($request->query('format') === 'json' || ($request->wantsJson() && ! $request->header('X-Inertia'))) {
            return response()->json([
                'success' => true,
                'data' => $resource,
            ]);
        }

        return Inertia::render('Admin/Order/Show', [
            'order' => $resource,
            'title' => 'Pesanan: ' . $order->order_number,
        ]);
    }

    /**
     * Show form for editing the specified order
     */
    public function edit(Order $order)
    {
        $order->load(['items.product.supplier', 'items.product.category']);

        $availableProducts = Product::where('is_active', true)
            ->with(['supplier:id,name', 'category:id,name'])
            ->orderBy('name')
            ->get(['id', 'supplier_id', 'category_id', 'name', 'sell_price', 'image_url', 'is_active']);

        $cmsSettings = CmsSetting::whereIn('key', ['dp_percentage', 'company_address', 'company_name'])->pluck('value', 'key');

        return Inertia::render('Admin/Order/Edit', [
            'order' => (new OrderResource($order))->resolve(request()),
            'availableProducts' => $availableProducts,
            'cmsSettings' => [
                'dp_percentage' => (int) ($cmsSettings['dp_percentage'] ?? 70),
                'company_address' => $cmsSettings['company_address'] ?? 'Jl. Boulevard Raya No. 88, Bekasi, Jawa Barat 17144',
                'company_name' => $cmsSettings['company_name'] ?? 'Padu Kue',
            ],
            'statuses' => [
                ['value' => 'pending', 'label' => 'Pending'],
                ['value' => 'diterima', 'label' => 'Diterima'],
                ['value' => 'diproses', 'label' => 'Diproses'],
                ['value' => 'dikemas', 'label' => 'Dikemas'],
                ['value' => 'dikirim', 'label' => 'Dikirim'],
                ['value' => 'selesai', 'label' => 'Selesai'],
            ],
            'title' => 'Edit Pesanan: ' . $order->order_number,
        ]);
    }

    /**
     * Update the specified order
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();
        $items = $validated['items'];
        unset($validated['items']);

        $this->orderService->updateOrder($order, $validated, $items);

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan ' . $order->order_number . ' berhasil diperbarui');
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
