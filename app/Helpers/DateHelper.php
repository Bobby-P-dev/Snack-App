<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format date to Indonesian locale
     */
    public static function formatDateId($date): string
    {
        $date = Carbon::parse($date);

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $date->day . ' ' . $months[$date->month] . ' ' . $date->year;
    }

    /**
     * Format date and time to Indonesian locale
     */
    public static function formatDateTimeId($dateTime): string
    {
        $dateTime = Carbon::parse($dateTime);

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $dateTime->day . ' ' . $months[$dateTime->month] . ' ' . $dateTime->year
            . ' - ' . $dateTime->format('H:i');
    }

    /**
     * Get human readable time difference
     */
    public static function humanDiffId($date): string
    {
        $date = Carbon::parse($date);
        $now = Carbon::now();

        if ($date->isToday()) {
            return 'Hari ini';
        } elseif ($date->isYesterday()) {
            return 'Kemarin';
        } else {
            return $date->diffInDays($now) . ' hari lalu';
        }
    }

    /**
     * Get next available pickup dates (excluding today and past dates)
     */
    public static function getNextPickupDates($days = 7): array
    {
        $dates = [];
        $startDate = Carbon::tomorrow();

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dates[] = [
                'date' => $date->format('Y-m-d'),
                'label' => self::formatDateId($date),
            ];
        }

        return $dates;
    }
}
