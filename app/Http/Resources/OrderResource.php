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
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'pickup_date' => $this->pickup_date->format('Y-m-d H:i:s'),
            'total_amount' => $this->total_amount,
            'dp_amount' => $this->dp_amount,
            'status' => $this->status,
            'items' => OrderItemResource::collection($this->items),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
