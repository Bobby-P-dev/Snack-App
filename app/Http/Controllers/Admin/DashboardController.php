<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;

class DashboardController extends Controller
{

    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'confirmedOrders' => Order::where('status', 'confirmed')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'totalRevenue' => Order::where('status', 'completed')->sum('total_amount'),
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
        ];

        // Get recent orders
        $recentOrders = Order::with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'title' => 'Dashboard',
            'subtitle' => 'Selamat datang!',
        ]);
    }
}
