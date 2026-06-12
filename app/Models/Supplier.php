<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'daily_capacity',
    ];

    protected $casts = [
        'daily_capacity' => 'integer',
    ];

    /**
     * Get all products from this supplier
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
