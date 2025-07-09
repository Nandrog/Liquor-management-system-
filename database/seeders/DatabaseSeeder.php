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
        $this->call([
            WarehouseSeeder::class,
    
            RoleSeeder::class,
            EmployeeSeeder::class
        ]);

        User::factory()->create([
            'firstname' => 'Test',
            'lastname'  => 'User',
            'username'  => 'testuser',
            'email'     => 'test@example.com',
        ]);
    }
}
