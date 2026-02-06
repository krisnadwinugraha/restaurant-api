<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $waiterRole = Role::create(['name' => 'waiter']);
        $cashierRole = Role::create(['name' => 'cashier']);

        $admin = User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        $waiter = User::create([
            'name' => 'John Waiter',
            'email' => 'waiter@restaurant.com',
            'password' => Hash::make('password'),
        ]);
        $waiter->assignRole($waiterRole);

        $cashier = User::create([
            'name' => 'Sarah Cashier',
            'email' => 'cashier@restaurant.com',
            'password' => Hash::make('password'),
        ]);
        $cashier->assignRole($cashierRole);
    }
}

