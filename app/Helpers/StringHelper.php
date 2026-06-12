<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    /**
     * Generate slug from string
     */
    public static function generateSlug($string): string
    {
        return Str::slug($string, '-');
    }

    /**
     * Truncate string with ellipsis
     */
    public static function truncate($string, $limit = 100, $end = '...'): string
    {
        return Str::limit($string, $limit, $end);
    }

    /**
     * Convert phone number to WhatsApp format
     * Input: 081234567890 or +6281234567890
     * Output: 6281234567890
     */
    public static function toWhatsAppFormat($phoneNumber): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phoneNumber);

        // If starts with 0, replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with 62, prepend it
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Validate Indonesia phone number
     */
    public static function isValidPhoneNumber($phoneNumber): bool
    {
        $phone = preg_replace('/\D/', '', $phoneNumber);

        // Check if starts with 0 or 62
        if (!str_starts_with($phone, '0') && !str_starts_with($phone, '62')) {
            return false;
        }

        // Check if length is valid (10-13 digits)
        if (strlen($phone) < 10 || strlen($phone) > 13) {
            return false;
        }

        return true;
    }

    /**
     * Format phone number for display
     * Input: 6281234567890
     * Output: 0812-3456-7890
     */
    public static function formatPhoneNumber($phoneNumber): string
    {
        $phone = preg_replace('/\D/', '', $phoneNumber);

        // Convert to 0 prefix if starts with 62
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        // Format: 0812-3456-7890
        return substr($phone, 0, 4) . '-' . substr($phone, 4, 4) . '-' . substr($phone, 8);
    }

    /**
     * Mask email address
     * Input: admin@example.com
     * Output: ad***@example.com
     */
    public static function maskEmail($email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];

        $maskedName = substr($name, 0, 2) . str_repeat('*', strlen($name) - 2);

        return $maskedName . '@' . $domain;
    }
}
