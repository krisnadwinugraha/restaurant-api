<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = [
            ['name' => 'Beef Burger', 'category' => 'food', 'price' => 12.50],
            ['name' => 'Chicken Pasta', 'category' => 'food', 'price' => 15.00],
            ['name' => 'Caesar Salad', 'category' => 'food', 'price' => 10.00],
            ['name' => 'Iced Lemon Tea', 'category' => 'drink', 'price' => 3.50],
            ['name' => 'Fresh Orange Juice', 'category' => 'drink', 'price' => 4.50],
            ['name' => 'Cappuccino', 'category' => 'drink', 'price' => 5.00],
        ];

        foreach ($menuItems as $item) {
            Food::create($item);
        }
    }
}
