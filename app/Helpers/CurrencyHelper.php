<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format currency to Indonesian Rupiah
     */
    public static function formatRupiah($amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Parse Rupiah string to integer
     */
    public static function parseRupiah($rupiahString): int
    {
        return (int) str_replace(['Rp ', '.', ','], '', $rupiahString);
    }

    /**
     * Calculate profit margin
     */
    public static function calculateProfit($basePrice, $sellPrice): float
    {
        if ($basePrice == 0) {
            return 0;
        }

        return ($sellPrice - $basePrice) / $basePrice * 100;
    }

    /**
     * Format profit margin with percentage
     */
    public static function formatProfitMargin($basePrice, $sellPrice): string
    {
        $profit = self::calculateProfit($basePrice, $sellPrice);
        return round($profit, 2) . '%';
    }
}
