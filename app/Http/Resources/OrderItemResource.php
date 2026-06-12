<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
            'price_at_order' => $this->price_at_order,
            'type' => $this->type,
            'box_group_id' => $this->box_group_id,
            'subtotal' => $this->quantity * $this->price_at_order,
        ];
    }
}
