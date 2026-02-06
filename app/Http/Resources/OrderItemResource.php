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
            'quantity' => $this->quantity,
            'price_at_order' => (float) $this->price,
            'subtotal' => (float) $this->subtotal,
            'food' => new FoodResource($this->whenLoaded('food')),
            'notes' => $this->notes,
        ];
    }
}
