<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // Find a user with the 'Liquor Manager' role
        $manager = User::role('Liquor Manager')->first();

        Warehouse::create([
            'name'         => 'Kampala Central Warehouse',
            'location'     => 'Kampala, Uganda',
            'capacity'     => 5000,
            'contact_info' => 'central@warehouse.ug',
            'manager_id'   => $manager->id ?? null,
        ]);

        Warehouse::create([
            'name'         => 'Jinja Regional Hub',
            'location'     => 'Jinja, Uganda',
            'capacity'     => 3000,
            'contact_info' => 'jinja@warehouse.ug',
            'manager_id'   => $manager->id ?? null,
        ]);
    }
}
