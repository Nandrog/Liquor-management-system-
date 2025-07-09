<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\WarehouseSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\EmployeeSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

         $this->call([
        RoleSeeder::class,
            
            WarehouseSeeder::class,
            FactorySeeder::class,
            UserSeeder::class, // Creates your admin, customer, supplier users
            CategorySeeder::class,
            VendorSeeder::class, 
            ProductSeeder::class,
            RecipeSeeder::class,
            StockLevelSeeder::class, // Sets the initial stock
            
    ]);

        User::factory()->create([
        'firstname' => 'Test',
        'lastname' => 'User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        ]);
   }

}
