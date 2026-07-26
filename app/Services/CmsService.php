<?php

namespace App\Services;

use App\Models\CmsSetting;
use App\Models\CmsCarousel;
use App\Models\CmsSocialMedia;
use Illuminate\Support\Facades\Cache;

class CmsService
{
    const CACHE_TTL = 86400; // 24 hours
    const CACHE_PREFIX = 'cms_';

    /**
     * Get single CMS setting
     */
    public function getSetting(string $key, $default = null)
    {
        $setting = CmsSetting::where('key', $key)->first();
        return $setting?->value ?? $default;
    }

    /**
     * Get all CMS settings
     */
    public function getAllSettings()
    {
        $settings = CmsSetting::all();
        return $settings->pluck('value', 'key')->toArray();
    }

    /**
     * Get carousel images
     */
    public function getCarousels()
    {
        return CmsCarousel::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    /**
     * Get social media links
     */
    public function getSocialMedia()
    {
        return CmsSocialMedia::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    /**
     * Update or create setting
     */
    public function updateSetting(string $key, $value, string $type = 'text', $description = null)
    {
        CmsSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'description' => $description,
            ]
        );

        // Clear cache
        $this->clearCache($key);
    }

    /**
     * Clear specific cache
     */
    public function clearCache($key = null)
    {
        if ($key) {
            Cache::forget(self::CACHE_PREFIX . $key);
        } else {
            Cache::flush(); // Clear all cache
        }
    }
}
