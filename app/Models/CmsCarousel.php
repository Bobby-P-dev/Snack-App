<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CmsCarousel extends Model
{
    protected $fillable = ['title', 'image_url', 'description', 'link_url', 'order', 'is_active', 'duration'];

    /**
     * Get the full S3/MinIO URL for the carousel image.
     */
    public function getImageUrlAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;
        return Storage::disk('s3')->url($value);
    }
}
