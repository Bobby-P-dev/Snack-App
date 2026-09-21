<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pickupDate = is_string($this->pickup_date) ? \Carbon\Carbon::parse($this->pickup_date) : $this->pickup_date;
        $createdAt = is_string($this->created_at) ? \Carbon\Carbon::parse($this->created_at) : $this->created_at;
        $updatedAt = is_string($this->updated_at) ? \Carbon\Carbon::parse($this->updated_at) : $this->updated_at;

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'pickup_date' => $pickupDate ? $pickupDate->format('Y-m-d H:i:s') : null,
            'location' => $this->location,
            'notes' => $this->notes,
            'total_amount' => $this->total_amount,
            'payment_type' => $this->payment_type ?? 'dp',
            'dp_amount' => $this->dp_amount,
            'status' => $this->status,
            'items' => $this->items ? OrderItemResource::collection($this->items)->resolve($request) : [],
            'created_at' => $createdAt ? $createdAt->format('Y-m-d H:i:s') : null,
            'updated_at' => $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
