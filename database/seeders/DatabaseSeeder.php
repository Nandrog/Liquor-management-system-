<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Database\Seeders\WarehouseSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\FactorySeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\VendorSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RecipeSeeder;
use Database\Seeders\StockLevelSeeder;
use Database\Seeders\StockMovementSeeder;
use Database\Seeders\OrderSeeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            WarehouseSeeder::class,
            EmployeeSeeder::class,
            FactorySeeder::class,
            UserSeeder::class, // Creates admin, supplier, manager etc.
            CategorySeeder::class,
            VendorSeeder::class,
            ProductSeeder::class,
            RecipeSeeder::class,
            StockLevelSeeder::class,
            StockMovementSeeder::class,
             
            OrderSeeder::class,

            VendorProductSeeder::class,
            OrderItemSeeder::class,

        ]);

        // ✅ Create a test user with password to avoid error
        User::firstOrCreate(
            ['username' => 'testuser'],
            [
                'firstname' => 'Test',
                'lastname' => 'User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
    }
}
