<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Hide base_price from customer view
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'sell_price' => $this->sell_price,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
            'category' => [
                'id' => $this->category ? $this->category->id : null,
                'name' => $this->category ? $this->category->name : '',
                'slug' => $this->category ? $this->category->slug : '',
            ],
        ];

        // Show sensitive supplier data and base_price only for authenticated admin users
        if ($request->user() || auth()->check()) {
            $data['base_price'] = $this->base_price;
            $data['supplier'] = [
                'id' => $this->supplier ? $this->supplier->id : null,
                'name' => $this->supplier ? $this->supplier->name : '',
            ];
        }

        return $data;
    }

    /**
     * Calculate profit margin
     */
    private function calculateProfitMargin(): float
    {
        if ($this->base_price == 0) {
            return 0;
        }
        return (($this->sell_price - $this->base_price) / $this->base_price) * 100;
    }
}
