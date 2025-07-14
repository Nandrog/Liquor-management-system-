<?php

namespace Database\Seeders;

use App\Models\Factory;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    public function run(): void
    {
        // Find the first available warehouse that doesn't already have a factory.
        $warehouse1 = Warehouse::doesntHave('factory')->oldest()->first();
        if ($warehouse1) {
            Factory::create([
                'name' => 'Main Production Plant',
                'location' => 'Kampala Industrial Area',
                'warehouse_id' => $warehouse1->warehouse_id,  // <-- changed here
            ]);
        } else {
            $this->command->warn('Could not find an available warehouse for the Main Production Plant.');
        }

        // Try to create a second factory if another warehouse is available
        $warehouse2 = Warehouse::doesntHave('factory')->oldest()->first();
        if ($warehouse2) {
             Factory::create([
                'name' => 'Jinja Bottling Facility',
                'location' => 'Jinja Industrial Zone',
                'warehouse_id' => $warehouse2->warehouse_id,  // <-- and here
            ]);
        }
        
        $this->command->info('Factories seeded successfully!');
    }
}
