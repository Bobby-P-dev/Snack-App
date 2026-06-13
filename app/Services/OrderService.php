<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Generate unique order number
     * Format: ORD-YYYYMMDD-XXXXX
     */
    public function generateOrderNumber(): string
    {
        $date = Carbon::now()->format('Ymd');
        $count = \App\Models\Order::whereDate('created_at', Carbon::today())->count() + 1;
        $number = str_pad($count, 5, '0', STR_PAD_LEFT);

        return "ORD-{$date}-{$number}";
    }

    /**
     * Generate WhatsApp message from order
     * Format sesuai CLAUDE.md
     */
    public function generateWhatsAppMessage($order, $businessName = 'Snack Box Custom'): string
    {
        $message = "INVOICE PESANAN - {$businessName}\n";
        $message .= "No Pesanan: {$order->order_number}\n";
        $message .= "Nama: {$order->customer_name}\n";
        $pickupDate = is_string($order->pickup_date) ? \Carbon\Carbon::parse($order->pickup_date) : $order->pickup_date;
        $message .= "Waktu: " . $pickupDate->format('d-m-Y H:i') . "\n";
        $message .= "Lokasi: {$order->location}\n\n";

        $customBoxes = $order->items()->where('type', 'kustom_box')->get();
        $singleItems = $order->items()->where('type', 'satuan')->get();

        // Group custom boxes by box_group_id
        if ($customBoxes->isNotEmpty()) {
            $message .= "[1] Pesanan Snack Box (Custom)\n\n";
            $groupedBoxes = $customBoxes->groupBy('box_group_id');

            foreach ($groupedBoxes as $groupId => $items) {
                $itemNames = $items->map(fn($item) => $item->product->name)->implode(', ');
                $quantity = $items->sum('quantity');
                $pricePerBox = $items->first()->price_at_order;
                $subtotal = $quantity * $pricePerBox;

                $message .= "- Jumlah: {$quantity} Box\n";
                $message .= "- Isi per Box: {$itemNames}\n";
                $message .= "- Harga per Box: Rp " . number_format($pricePerBox, 0, ',', '.') . "\n";
                $message .= "  Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
            }
        }

        // Single items
        if ($singleItems->isNotEmpty()) {
            $message .= "[2] Pesanan Kue Satuan\n\n";

            foreach ($singleItems as $item) {
                $quantity = $item->quantity;
                $itemName = $item->product->name;
                $price = $item->price_at_order;
                $subtotal = $quantity * $price;

                $message .= "- {$quantity}x {$itemName} @ Rp " . number_format($price, 0, ',', '.') . "\n";
                $message .= "  Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            }
        }

        $message .= "\nTOTAL TAGIHAN: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n";
        $message .= "DP (50%): Rp " . number_format($order->dp_amount, 0, ',', '.') . "\n";

        return $message;
    }

    /**
     * Generate WhatsApp URL for checkout
     */
    public function generateWhatsAppUrl($order, $adminPhone, $businessName = 'Snack Box Custom'): string
    {
        $message = $this->generateWhatsAppMessage($order, $businessName);
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$adminPhone}?text={$encodedMessage}";
    }

    /**
     * Create order with items
     */
    public function createOrder(array $orderData, array $items): \App\Models\Order
    {
        // Generate order number
        $orderData['order_number'] = $this->generateOrderNumber();

        // Calculate total and DP
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price_at_order'] * $item['quantity'];
        }

        $orderData['total_amount'] = $total;
        $orderData['dp_amount'] = $total * 0.5; // 50% DP

        // Create order
        $order = $this->orderRepository->create($orderData);

        // Create order items
        foreach ($items as $item) {
            $order->items()->create($item);
        }

        return $order;
    }

    /**
     * Update order status
     */
    public function updateOrderStatus($orderId, $status): \App\Models\Order
    {
        return $this->orderRepository->update($orderId, ['status' => $status]);
    }

    /**
     * Confirm order
     */
    public function confirmOrder($orderId): \App\Models\Order
    {
        return $this->updateOrderStatus($orderId, 'confirmed');
    }

    /**
     * Complete order
     */
    public function completeOrder($orderId): \App\Models\Order
    {
        return $this->updateOrderStatus($orderId, 'completed');
    }
}
