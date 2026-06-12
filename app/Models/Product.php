<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'supplier_id',
        'category_id',
        'name',
        'base_price',
        'sell_price',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Configure activity logging
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'base_price', 'sell_price', 'is_active'])
            ->useLogName('product')
            ->logOnlyDirty();
    }

    /**
     * Get the supplier of this product
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the category of this product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all order items containing this product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
