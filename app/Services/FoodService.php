<?php

namespace App\Services;

use App\Models\Food;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class FoodService
{
    public function getAllFood(?string $category = null): Collection
    {
        $query = Food::query();
        
        // $query->with('orderItems'); 

        if ($category) {
            $query->where('category', $category);
        }

        return $query->orderBy('name')->get();
    }

    public function createFood(array $data): Food
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('foods', 'public');
        }

        return Food::create($data);
    }

   public function updateFood(Food $food, array $data): Food
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($food->image) {
                Storage::disk('public')->delete($food->image);
            }
            $data['image'] = $data['image']->store('foods', 'public');
        }

        $food->update($data);
        return $food;
    }

    public function deleteFood(Food $food): void
    {
        $food->delete();
    }

    public function forceDeleteFood(Food $food): void
    {
        if ($food->image) {
            Storage::disk('public')->delete($food->image);
        }
        $food->forceDelete();
    }
}