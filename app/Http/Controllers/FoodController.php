<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Services\FoodService;
use App\Http\Resources\FoodResource;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest; 
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware; 
use Illuminate\Routing\Controllers\Middleware;  

class FoodController extends Controller implements HasMiddleware
{
    public function __construct(
        protected FoodService $foodService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            
            new Middleware('role:waiter', only: ['store', 'update', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        $foods = $this->foodService->getAllFood($request->category);
        return FoodResource::collection($foods);
    }

    public function show(Food $food)
    {
        return new FoodResource($food);
    }

    public function store(StoreFoodRequest $request)
    {
        $food = $this->foodService->createFood($request->validated());
        return new FoodResource($food);
    }

    public function update(UpdateFoodRequest $request, Food $food)
    {
        $updatedFood = $this->foodService->updateFood($food, $request->validated());
        return new FoodResource($updatedFood);
    }

    public function destroy(Food $food)
    {
        $this->foodService->deleteFood($food);
        return response()->json(null, 204);
    }
}