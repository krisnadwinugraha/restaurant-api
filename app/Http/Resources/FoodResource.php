<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
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
            'name' => $this->name,
            'category' => $this->category,
            'price' => (float) $this->price,
            'description' => $this->description,
            'image_url' => $this->image ? asset('storage/' . $this->image) : asset('images/default-food.png'),
            'is_available' => (bool) $this->is_available,
        ];
    }
}
