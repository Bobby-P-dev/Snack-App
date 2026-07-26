<?php

namespace App\Services;

use App\Models\CmsSetting;
use App\Models\Order;
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
        $count = Order::whereDate('created_at', Carbon::today())->count() + 1;
        $number = str_pad($count, 5, '0', STR_PAD_LEFT);

        return "ORD-{$date}-{$number}";
    }

    /**
     * Generate WhatsApp message from order
     * Format sesuai CLAUDE.md
     */
    public function generateWhatsAppMessage($order): string
    {
        $template = "INVOICE PESANAN tes aja - {company_name}\n"
            . "No Pesanan: {order_number}\n"
            . "Nama: {customer_name}\n"
            . "WhatsApp: {customer_phone}\n"
            . "Pengambilan: {pickup_date}\n"
            . "Lokasi: {customer_location}\n"
            . "Catatan: {notes}\n\n"
            . "{order_list}\n"
            . "TOTAL TAGIHAN: Rp {total_amount}\n"
            . "DP ({dp_percentage}%): Rp {dp_amount}\n"
            . "Sisa bayar: Rp {remaining_amount} (dibayar saat pengambilan)";

        $pickupDate = is_string($order->pickup_date) ? Carbon::parse($order->pickup_date) : $order->pickup_date;
        $pickupDate->locale('id'); // Set locale ke bahasa Indonesia

        $dpSetting = CmsSetting::where('key', 'dp_percentage')->first();
        $dpPercentage = $dpSetting ? (int) $dpSetting->value : 70;
        $dpAmount = ($order->total_amount * $dpPercentage) / 100;
        $remaining = $order->total_amount - $dpAmount;

        $orderListStr = "";
        $customBoxes = $order->items()->where('type', 'kustom_box')->get();
        $singleItems = $order->items()->where('type', 'satuan')->get();

        if ($customBoxes->isNotEmpty()) {
            $orderListStr .= "[1] Pesanan Snack Box (Custom)\n";
            $groupedBoxes = $customBoxes->groupBy('box_group_id');
            foreach ($groupedBoxes as $groupId => $items) {
                $itemNames = $items->map(fn($item) => "  - " . $item->product->name)->implode("\n");
                $quantity = $items->sum('quantity');
                $pricePerBox = $items->first()->price_at_order;
                $subtotal = $quantity * $pricePerBox;

                $orderListStr .= "- Jumlah: {$quantity} Box\n";
                $orderListStr .= "- Isi per Box:\n{$itemNames}\n";
                $orderListStr .= "- Harga per Box: Rp " . number_format($pricePerBox, 0, ',', '.') . "\n";
                $orderListStr .= "  Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
            }
        }

        if ($singleItems->isNotEmpty()) {
            $orderListStr .= "[2] Pesanan Kue Satuan\n";
            foreach ($singleItems as $item) {
                $quantity = $item->quantity;
                $itemName = $item->product->name;
                $price = $item->price_at_order;
                $subtotal = $quantity * $price;

                $orderListStr .= "- {$quantity}x {$itemName}\n";
                $orderListStr .= "  Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            }
            $orderListStr .= "\n";
        }

        $businnesName = CmsSetting::where('key', 'company_name')->first();
        if (!$businnesName) {
            $businnesName = 'Padu Kue';
        } else {
            $businnesName = $businnesName->value;
        }

        $replacements = [
            '{company_name}' => $businnesName,
            '{order_number}' => $order->order_number,
            '{customer_name}' => $order->customer_name,
            '{customer_phone}' => $order->customer_phone,
            '{pickup_date}' => $pickupDate->isoFormat('dddd, D MMMM YYYY [pukul] HH.mm'),
            '{customer_location}' => $order->location,
            '{notes}' => $order->notes ?? '-',
            '{order_list}' => trim($orderListStr),
            '{total_amount}' => number_format($order->total_amount, 0, ',', '.'),
            '{dp_percentage}' => $dpPercentage,
            '{dp_amount}' => number_format($dpAmount, 0, ',', '.'),
            '{remaining_amount}' => number_format($remaining, 0, ',', '.'),
        ];

        // Replace semua keyword {..} dengan data aslinya
        $message = str_replace(array_keys($replacements), array_values($replacements), $template);

        // Lampirkan link download invoice PDF di bagian terbawah
        $message .= "\n\nDownload Invoice PDF Anda di sini:\n" . route('pdf.invoice', $order->id);

        return trim($message);
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

        $dpSetting = \App\Models\CmsSetting::where('key', 'dp_percentage')->first();
        $dpPercentage = $dpSetting ? (int) $dpSetting->value : 50;
        $orderData['dp_amount'] = ($total * $dpPercentage) / 100;

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
