<?php

namespace App\Services;

use App\Models\CmsSetting;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
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
     * Generate secure, unique, non-sequential order number
     * Format: PK-XXXXXXXX (e.g. PK-7K4MP9QX)
     */
    public function generateOrderNumber(): string
    {
        $chars = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
        do {
            $random = '';
            for ($i = 0; $i < 8; $i++) {
                $random .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $orderNumber = "PK-{$random}";
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Generate WhatsApp message from order
     */
    public function generateWhatsAppMessage($order, ?string $businessNameOverride = null): string
    {
        $templateSetting = CmsSetting::where('key', 'wa_checkout_template')->first();
        $template = $templateSetting?->value;

        $dpSetting = CmsSetting::where('key', 'dp_percentage')->first();
        $defaultDpPercentage = $dpSetting ? (int) $dpSetting->value : 70;
        $isFull = (($order->payment_type ?? 'dp') === 'full' || (float)$order->dp_amount >= (float)$order->total_amount);

        $dpAmount = (float) $order->dp_amount;
        $remaining = max(0, (float)$order->total_amount - $dpAmount);
        $dpPercentage = $isFull ? 100 : $defaultDpPercentage;

        if (empty($template)) {
            if ($isFull) {
                $template = "INVOICE PESANAN - {company_name}\n"
                    . "No Pesanan: {order_number}\n"
                    . "Nama: {customer_name}\n"
                    . "WhatsApp: {customer_phone}\n"
                    . "Pengambilan: {pickup_date}\n"
                    . "Lokasi: {customer_location}\n"
                    . "Catatan: {notes}\n\n"
                    . "{order_list}\n\n"
                    . "TOTAL TAGIHAN: Rp {total_amount}\n"
                    . "PEMBAYARAN: Bayar Penuh (100%) - Rp {total_amount}\n"
                    . "Sisa bayar: Rp 0 (Lunas)\n\n"
                    . "Download Invoice PDF Anda di sini:\n{invoice_url}\n\n"
                    . "Pantau status pesanan Anda di sini:\n{tracking_url}";
            } else {
                $template = "INVOICE PESANAN - {company_name}\n"
                    . "No Pesanan: {order_number}\n"
                    . "Nama: {customer_name}\n"
                    . "WhatsApp: {customer_phone}\n"
                    . "Pengambilan: {pickup_date}\n"
                    . "Lokasi: {customer_location}\n"
                    . "Catatan: {notes}\n\n"
                    . "{order_list}\n\n"
                    . "TOTAL TAGIHAN: Rp {total_amount}\n"
                    . "DP ({dp_percentage}%): Rp {dp_amount}\n"
                    . "Sisa bayar: Rp {remaining_amount} (dibayar saat pengambilan)\n\n"
                    . "Download Invoice PDF Anda di sini:\n{invoice_url}\n\n"
                    . "Pantau status pesanan Anda di sini:\n{tracking_url}";
            }
        } else {
            // Ensure clean blank line between product list and total tagihan
            $template = preg_replace('/\{order_list\}\s*\n\s*TOTAL TAGIHAN/i', "{order_list}\n\nTOTAL TAGIHAN", $template);

            if ($isFull) {
                // If template contains the standard DP lines, automatically adapt to Bayar Penuh
                $dpPattern = '/DP\s*\(\s*\{dp_percentage\}%\s*\):\s*Rp\s*\{dp_amount\}\s*\n\s*Sisa bayar:\s*Rp\s*\{remaining_amount\}\s*\(dibayar saat pengambilan\)/i';
                if (preg_match($dpPattern, $template)) {
                    $template = preg_replace(
                        $dpPattern,
                        "PEMBAYARAN: Bayar Penuh (100%) - Rp {total_amount}\nSisa bayar: Rp 0 (Lunas)",
                        $template
                    );
                }
            }
        }

        $pickupDate = is_string($order->pickup_date) ? Carbon::parse($order->pickup_date) : $order->pickup_date;
        $pickupDate->locale('id');

        $orderListStr = "";
        $customBoxes = $order->items()->where('type', 'kustom_box')->get();
        $singleItems = $order->items()->where('type', 'satuan')->get();

        $hasCustomBoxes = $customBoxes->isNotEmpty();
        $hasSingleItems = $singleItems->isNotEmpty();

        if ($hasCustomBoxes) {
            $orderListStr .= $hasSingleItems ? "[1] Pesanan Snack Box (Custom)\n" : "Pesanan Snack Box (Custom)\n";
            $groupedBoxes = $customBoxes->groupBy('box_group_id');
            foreach ($groupedBoxes as $groupId => $items) {
                // Calculate box quantity using greatest common divisor of item quantities
                $gcd = function ($a, $b) use (&$gcd) {
                    return $b ? $gcd($b, $a % $b) : $a;
                };
                $boxQuantity = $items->pluck('quantity')->reduce(fn($carry, $q) => $carry ? $gcd($carry, (int)$q) : (int)$q, 0);
                if ($boxQuantity <= 0) {
                    $boxQuantity = (int) $items->first()->quantity;
                }

                $subtotal = (float) $items->sum(fn($item) => $item->quantity * $item->price_at_order);
                $pricePerBox = $boxQuantity > 0 ? (int) round($subtotal / $boxQuantity) : (int) $subtotal;

                $itemNames = $items->map(function ($item) use ($boxQuantity) {
                    $name = $item->product ? $item->product->name : 'Produk';
                    $qtyPerBox = $boxQuantity > 0 ? (int) round($item->quantity / $boxQuantity) : 1;
                    return $qtyPerBox > 1 ? "  - {$qtyPerBox}x {$name}" : "  - {$name}";
                })->implode("\n");

                $orderListStr .= "- Jumlah: {$boxQuantity} Box\n";
                $orderListStr .= "- Isi per Box:\n{$itemNames}\n";
                $orderListStr .= "- Harga per Box: Rp " . number_format($pricePerBox, 0, ',', '.') . "\n";
                $orderListStr .= "- Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
            }
        }

        if ($hasSingleItems) {
            $orderListStr .= $hasCustomBoxes ? "[2] Pesanan Kue Satuan\n" : "Pesanan Kue Satuan\n";
            foreach ($singleItems as $item) {
                $quantity = $item->quantity;
                $itemName = $item->product ? $item->product->name : 'Produk';
                $price = $item->price_at_order;
                $subtotal = $quantity * $price;

                $orderListStr .= "- {$quantity}x {$itemName}: Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            }
            $orderListStr .= "\n";
        }

        if ($businessNameOverride) {
            $companyName = $businessNameOverride;
        } else {
            $businessName = CmsSetting::where('key', 'company_name')->first();
            $companyName = $businessName ? $businessName->value : 'Padu Kue';
        }

        $invoiceUrl = route('pdf.invoice', $order->order_number);
        $trackingUrl = route('tracking.index', ['order_number' => $order->order_number]);

        $replacements = [
            '{company_name}' => $companyName,
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
            '{payment_type}' => $isFull ? 'Bayar Full (100%)' : "DP ({$dpPercentage}%)",
            '{invoice_url}' => $invoiceUrl,
            '{tracking_url}' => $trackingUrl,
        ];

        $message = str_replace(array_keys($replacements), array_values($replacements), $template);

        // If custom template didn't provide {invoice_url}, append it for safety
        if (!str_contains($template, '{invoice_url}')) {
            $message .= "\n\nDownload Invoice PDF Anda di sini:\n" . $invoiceUrl;
        }

        // If custom template didn't provide {tracking_url}, append it for convenience
        if (!str_contains($template, '{tracking_url}')) {
            $message .= "\n\nPantau status pesanan Anda di sini:\n" . $trackingUrl;
        }

        return trim($message);
    }

    /**
     * Generate WhatsApp URL for checkout
     */
    public function generateWhatsAppUrl($order, $adminPhone, $businessName = 'Padu Kue'): string
    {
        $message = $this->generateWhatsAppMessage($order, $businessName);
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$adminPhone}?text={$encodedMessage}";
    }

    /**
     * Create order with items inside a database transaction
     */
    public function createOrder(array $orderData, array $items): \App\Models\Order
    {
        return DB::transaction(function () use ($orderData, $items) {
            // Generate secure random order number
            $orderData['order_number'] = $this->generateOrderNumber();
            $orderData['status'] = 'pending';

            // Calculate total and DP
            $total = 0;
            foreach ($items as $item) {
                $total += $item['price_at_order'] * $item['quantity'];
            }

            $orderData['total_amount'] = $total;

            $paymentType = $orderData['payment_type'] ?? 'dp';
            if ($paymentType === 'full') {
                $orderData['payment_type'] = 'full';
                $orderData['dp_amount'] = $total;
            } else {
                $orderData['payment_type'] = 'dp';
                $dpSetting = CmsSetting::where('key', 'dp_percentage')->first();
                $dpPercentage = $dpSetting ? (int) $dpSetting->value : 70;
                $orderData['dp_amount'] = ($total * $dpPercentage) / 100;
            }

            // Determine package_type based on items
            $hasBox = collect($items)->contains(fn ($i) => ($i['type'] ?? '') === 'kustom_box');
            $hasSatuan = collect($items)->contains(fn ($i) => ($i['type'] ?? '') === 'satuan');

            if ($hasBox && $hasSatuan) {
                $orderData['package_type'] = 'campuran';
            } elseif ($hasBox) {
                $orderData['package_type'] = 'snack_box';
            } else {
                $orderData['package_type'] = $orderData['package_type'] ?? 'satuan';
            }

            // Create order
            $order = $this->orderRepository->create($orderData);

            // Create order items
            foreach ($items as $item) {
                $order->items()->create($item);
            }

            return $order;
        });
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
        return $this->updateOrderStatus($orderId, 'diterima');
    }

    /**
     * Complete order
     */
    public function completeOrder($orderId): \App\Models\Order
    {
        return $this->updateOrderStatus($orderId, 'selesai');
    }

    /**
     * Update existing order and synchronize its items inside a database transaction
     */
    public function updateOrder(Order $order, array $orderData, array $items): Order
    {
        return DB::transaction(function () use ($order, $orderData, $items) {
            // Determine package_type based on items
            $hasBox = collect($items)->contains(fn ($i) => ($i['type'] ?? '') === 'kustom_box');
            $hasSatuan = collect($items)->contains(fn ($i) => ($i['type'] ?? '') === 'satuan');

            if ($hasBox && $hasSatuan) {
                $orderData['package_type'] = 'campuran';
            } elseif ($hasBox) {
                $orderData['package_type'] = 'snack_box';
            } else {
                $orderData['package_type'] = 'satuan';
            }

            // Update order attributes
            $order->update(collect($orderData)->only([
                'customer_name',
                'customer_phone',
                'pickup_date',
                'location',
                'notes',
                'total_amount',
                'package_type',
                'payment_type',
                'dp_amount',
                'status',
            ])->toArray());

            // Sync items: delete removed items, update existing, create new
            $existingItemIds = $order->items()->pluck('id')->toArray();
            $submittedItemIds = collect($items)->pluck('id')->filter()->map(fn($id) => (int)$id)->toArray();

            // Delete items no longer in submitted list
            $toDelete = array_diff($existingItemIds, $submittedItemIds);
            if (!empty($toDelete)) {
                $order->items()->whereIn('id', $toDelete)->delete();
            }

            // Update or insert items
            foreach ($items as $itemData) {
                $itemId = !empty($itemData['id']) ? (int)$itemData['id'] : null;
                $payload = [
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'price_at_order' => $itemData['price_at_order'],
                    'type' => $itemData['type'] ?? 'satuan',
                    'box_group_id' => $itemData['box_group_id'] ?? null,
                ];

                if ($itemId && in_array($itemId, $existingItemIds)) {
                    $order->items()->where('id', $itemId)->update($payload);
                } else {
                    $order->items()->create($payload);
                }
            }

            return $order->fresh(['items.product.supplier', 'items.product.category']);
        });
    }
}
