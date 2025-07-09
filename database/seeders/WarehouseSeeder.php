<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // Find a user with the Liquor Manager role to assign as manager
        $manager = User::role('Liquor Manager')->first();

        Warehouse::create([
            'name' => 'Kampala Central Warehouse',
            'location' => 'Kampala, Uganda',
            'manager_id' => $manager->id ?? null,
        ]);

        Warehouse::create([
            'name' => 'Jinja Regional Hub',
            'location' => 'Jinja, Uganda',
            'manager_id' => $manager->id ?? null,
        ]);
    }
}
