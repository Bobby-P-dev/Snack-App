<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'pickup_date',
        'location',
        'total_amount',
        'dp_amount',
        'status',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'dp_amount' => 'decimal:2',
    ];

    /**
     * Configure activity logging
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'total_amount', 'dp_amount'])
            ->useLogName('order')
            ->logOnlyDirty();
    }

    /**
     * Get all items in this order
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
