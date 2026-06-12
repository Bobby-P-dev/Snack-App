<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Helpers\CurrencyHelper;

class CartService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Calculate total price for items
     */
    public function calculateTotal(array $items): array
    {
        $total = 0;
        $itemCount = 0;
        $details = [];

        foreach ($items as $item) {
            $product = $this->productRepository->find($item['product_id']);
            $subtotal = $product->sell_price * $item['quantity'];
            $total += $subtotal;
            $itemCount += $item['quantity'];

            $details[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'price' => $product->sell_price,
                'subtotal' => $subtotal,
                'supplier_id' => $product->supplier_id,
            ];
        }

        return [
            'total' => $total,
            'dp' => $total * 0.5, // 50% DP
            'item_count' => $itemCount,
            'details' => $details,
        ];
    }

    /**
     * Validate cart items (check product exists, is active, etc)
     */
    public function validateCartItems(array $items): array
    {
        $errors = [];

        foreach ($items as $index => $item) {
            try {
                $product = $this->productRepository->find($item['product_id']);

                if (!$product->is_active) {
                    $errors[] = "Produk '{$product->name}' tidak tersedia";
                }

                if ($item['quantity'] <= 0) {
                    $errors[] = "Jumlah produk harus lebih dari 0";
                }
            } catch (\Exception $e) {
                $errors[] = "Produk dengan ID {$item['product_id']} tidak ditemukan";
            }
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }

    /**
     * Group items by supplier for custom box
     */
    public function groupBySupplier(array $items): array
    {
        $grouped = [];

        foreach ($items as $item) {
            $product = $this->productRepository->find($item['product_id']);
            $supplierId = $product->supplier_id;

            if (!isset($grouped[$supplierId])) {
                $grouped[$supplierId] = [
                    'supplier' => $product->supplier,
                    'items' => [],
                ];
            }

            $grouped[$supplierId]['items'][] = [
                'product' => $product,
                'quantity' => $item['quantity'],
            ];
        }

        return $grouped;
    }

    /**
     * Calculate profit margin for order
     */
    public function calculateProfitMargin(array $items): array
    {
        $totalBasePrice = 0;
        $totalSellPrice = 0;

        foreach ($items as $item) {
            $product = $this->productRepository->find($item['product_id']);
            $totalBasePrice += $product->base_price * $item['quantity'];
            $totalSellPrice += $product->sell_price * $item['quantity'];
        }

        $profit = $totalSellPrice - $totalBasePrice;
        $profitMargin = ($profit / $totalBasePrice) * 100;

        return [
            'total_base_price' => $totalBasePrice,
            'total_sell_price' => $totalSellPrice,
            'profit' => $profit,
            'profit_margin' => round($profitMargin, 2),
            'profit_formatted' => CurrencyHelper::formatRupiah($profit),
        ];
    }
}
