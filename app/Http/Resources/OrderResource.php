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
            'status' => $this->status,
            'total_amount' => (float) $this->total_amount,
            'opened_at' => $this->opened_at,
            'closed_at' => $this->closed_at,
            
            'table' => new TableResource($this->whenLoaded('table')),
            'waiter' => new UserResource($this->whenLoaded('waiter')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
