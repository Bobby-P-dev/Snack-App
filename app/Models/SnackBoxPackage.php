<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnackBoxPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'capacity',
        'box_price',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'box_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
