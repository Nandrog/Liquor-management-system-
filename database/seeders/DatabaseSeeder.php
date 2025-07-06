<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

         $this->call([
        RoleSeeder::class,
            
            WarehouseSeeder::class,
            FactorySeeder::class,
            UserSeeder::class, // Creates your admin, customer, supplier users
            CategorySeeder::class, 
            ProductSeeder::class,
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
