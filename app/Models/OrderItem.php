<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'box_group_id',
        'quantity',
        'price_at_order',
        'type',
    ];

    protected $casts = [
        'price_at_order' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the order this item belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product in this order item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
