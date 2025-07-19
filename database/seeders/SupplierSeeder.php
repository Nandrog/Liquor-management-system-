<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        // Use a transaction for safety
        DB::transaction(function () {
            // 1. Get all users that have the 'Supplier' role assigned.
            $supplierUsers = User::role('Supplier')->get();

            if ($supplierUsers->isEmpty()) {
                $this->command->warn('No users with the "Supplier" role found. Skipping SupplierSeeder.');
                return;
            }

            // 2. Prepare some sample data to make the seeder interesting.
            $sampleData = [
                ['location' => 'Kampala', 'item-supplied' => 'Agricultural Goods', 'phone' => '256777111222'],
                ['location' => 'Jinja', 'item-supplied' => 'Packaging Materials', 'phone' => '256777333444'],
                ['location' => 'Mbarara', 'item-supplied' => 'Chemicals & Yeast', 'phone' => '256777555666'],
                ['location' => 'Gulu', 'item-supplied' => 'Barley & Hops', 'phone' => '256777777888'],
                ['location' => 'Mbale', 'item-supplied' => 'Cassava & Molasses', 'phone' => '256777999000'],
                ['location' => 'Entebbe', 'item-supplied' => 'Marketing Materials', 'phone' => '256777123123'],
                ['location' => 'Namanve', 'item-supplied' => 'Industrial Chemicals', 'phone' => '256777444555'],
                ['location' => 'Kenya', 'item-supplied' => 'Specialty Botanicals', 'phone' => '254777666777'],
            ];

            // 3. Loop through each supplier user and create a profile for them.
            foreach ($supplierUsers as $index => $supplierUser) {
                // Use updateOrCreate to prevent duplicates if the seeder is run multiple times.
                // It finds a supplier with the user_id, or creates a new one.
                Supplier::updateOrCreate(
                    ['user_id' => $supplierUser->id],
                    [
                        // Cycle through the sample data using the modulo operator
                        'location' => $sampleData[$index % count($sampleData)]['location'],
                        'item-supplied' => $sampleData[$index % count($sampleData)]['item-supplied'],
                        'phone' => $sampleData[$index % count($sampleData)]['phone'],
                    ]
                );
            }
        });

        $this->command->info('Supplier profiles seeded successfully!');
    }
}