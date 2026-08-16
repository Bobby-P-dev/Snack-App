<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\CmsSetting;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $cms = CmsSetting::first() ?? new CmsSetting();
        $orderNumber = $request->query('order_number');
        $orderData = null;
        $error = null;

        if ($orderNumber) {
            $order = Order::with('items.product')->where('order_number', $orderNumber)->first();
            
            if ($order) {
                // Map the DB status to the timeline steps
                $statusMap = [
                    'pending' => 1,
                    'di terima' => 2,
                    'di proses' => 3,
                    'di kemas' => 4,
                    'dikirim' => 5,
                    'selesai' => 6,
                ];

                $currentStep = $statusMap[strtolower($order->status)] ?? 1;

                // Build a simple summary of items
                $items = $order->items->map(function ($item) {
                    return [
                        'name' => $item->product ? $item->product->name : 'Unknown Product',
                        'quantity' => $item->quantity,
                        'price' => $item->price_at_order,
                        'image_url' => $item->product ? $item->product->image_url : null,
                    ];
                });

                $orderData = [
                    'order_number' => $order->order_number,
                    'date' => $order->created_at->format('d M Y, H:i') . ' WIB',
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'location' => $order->location,
                    'status' => $order->status,
                    'current_step' => $currentStep,
                    'items' => $items,
                    'subtotal' => $order->total_amount, // Assuming total_amount is subtotal
                    'ongkir' => 0, // Not present in the DB schema provided, assuming 0
                    'total' => $order->total_amount,
                ];
            } else {
                $error = 'Pesanan dengan Order Number tersebut tidak ditemukan.';
            }
        }

        return Inertia::render('Customer/Tracking/Index', [
            'cms' => $cms,
            'searchedOrderNumber' => $orderNumber,
            'order' => $orderData,
            'error' => $error,
        ]);
    }
}
