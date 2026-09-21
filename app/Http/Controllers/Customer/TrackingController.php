<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\CmsSetting;
use Carbon\Carbon;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $cmsSettings = CmsSetting::all()->pluck('value', 'key')->toArray();
        $orderNumber = $request->query('order_number');
        $orderData = null;
        $error = null;

        if ($orderNumber) {
            $trimmedOrderNumber = trim($orderNumber);
            $order = Order::with('items.product')->where('order_number', $trimmedOrderNumber)->first();
            
            if ($order) {
                // Map the DB status to the timeline steps
                $statusMap = [
                    'pending' => 1,
                    'diterima' => 2,
                    'di terima' => 2,
                    'diproses' => 3,
                    'di proses' => 3,
                    'dikemas' => 4,
                    'di kemas' => 4,
                    'dikirim' => 5,
                    'selesai' => 6,
                    'batal' => 0,
                ];

                $currentStep = $statusMap[strtolower($order->status)] ?? 1;

                // Build a clean summary of items
                $items = $order->items->map(function ($item) {
                    return [
                        'name' => $item->product ? $item->product->name : 'Produk Pilihan',
                        'quantity' => $item->quantity,
                        'price' => $item->price_at_order,
                        'type' => $item->type ?? 'satuan',
                        'image_url' => $item->product ? $item->product->image_url : null,
                    ];
                });

                // Mask phone number for privacy protection: e.g. 0812****7890
                $rawPhone = $order->customer_phone ?? '';
                $maskedPhone = strlen($rawPhone) > 7 
                    ? substr($rawPhone, 0, 4) . '****' . substr($rawPhone, -4)
                    : '****';

                // Format pickup date if available
                $pickupDateFormatted = null;
                if ($order->pickup_date) {
                    try {
                        $carbonPickup = Carbon::parse($order->pickup_date)->locale('id');
                        $pickupDateFormatted = $carbonPickup->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm [WIB]');
                    } catch (\Throwable $e) {
                        $pickupDateFormatted = (string) $order->pickup_date;
                    }
                }

                // DP & Remaining calculations based on actual order payment
                $defaultDpPercentage = isset($cmsSettings['dp_percentage']) ? (int) $cmsSettings['dp_percentage'] : 70;
                $dpAmount = (float) $order->dp_amount;
                $isFull = (($order->payment_type ?? 'dp') === 'full' || $dpAmount >= (float) $order->total_amount);
                $dpPercentage = $isFull ? 100 : $defaultDpPercentage;
                $remainingAmount = max(0, (float) $order->total_amount - $dpAmount);

                $orderData = [
                    'order_number' => $order->order_number,
                    'date' => $order->created_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm [WIB]'),
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $maskedPhone,
                    'location' => $order->location,
                    'pickup_date' => $pickupDateFormatted,
                    'notes' => $order->notes,
                    'status' => strtolower($order->status),
                    'current_step' => $currentStep,
                    'items' => $items,
                    'subtotal' => $order->total_amount,
                    'ongkir' => 0,
                    'total' => $order->total_amount,
                    'payment_type' => $order->payment_type ?? ($isFull ? 'full' : 'dp'),
                    'is_full' => $isFull,
                    'dp_percentage' => $dpPercentage,
                    'dp_amount' => $dpAmount,
                    'remaining_amount' => $remainingAmount,
                    'download_invoice_url' => route('pdf.invoice', $order->order_number),
                ];
            } else {
                $error = 'Pesanan dengan nomor "' . htmlspecialchars($orderNumber) . '" tidak ditemukan. Mohon periksa kembali nomor pesanan Anda.';
            }
        }

        return Inertia::render('Customer/Tracking/Index', [
            'cms' => $cmsSettings,
            'searchedOrderNumber' => $orderNumber,
            'order' => $orderData,
            'error' => $error,
        ]);
    }
}
