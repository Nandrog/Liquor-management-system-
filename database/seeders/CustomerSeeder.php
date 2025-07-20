<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This will create a profile in the 'customers' table for each user with the 'Customer' role.
     */
    public function run(): void
    {
        // Use a transaction for safety
        DB::transaction(function () {
            // 1. Get all users that have the 'Customer' role assigned.
            $customerUsers = User::role('Customer')->get();

            if ($customerUsers->isEmpty()) {
                $this->command->warn('No users with the "Customer" role found. Skipping CustomerSeeder.');
                return;
            }

            // 2. Prepare some sample data to make the profiles interesting.
            $sampleData = [
                ['city' => 'Kampala', 'state' => 'Central', 'phone_number' => '256701123456', 'location' => 'Acacia Mall'],
                ['city' => 'Jinja', 'state' => 'Eastern', 'phone_number' => '256702234567', 'location' => 'Source of the Nile Hotel'],
                ['city' => 'Mbarara', 'state' => 'Western', 'phone_number' => '256703345678', 'location' => 'Igongo Cultural Centre'],
                ['city' => 'Gulu', 'state' => 'Northern', 'phone_number' => '256704456789', 'location' => 'Gulu Main Market'],
            ];

            // 3. Loop through each customer user and create a profile for them.
            foreach ($customerUsers as $index => $customerUser) {
                // Use updateOrCreate to find a customer with the user_id, or create a new one.
                // This prevents creating duplicate profiles if the seeder is run again.
                Customer::updateOrCreate(
                    ['user_id' => $customerUser->id],
                    [
                        // Cycle through the sample data using the modulo operator for variety
                        'city' => $sampleData[$index % count($sampleData)]['city'],
                        'state' => $sampleData[$index % count($sampleData)]['state'],
                        'phone_number' => $sampleData[$index % count($sampleData)]['phone_number'],
                        'location' => $sampleData[$index % count($sampleData)]['location'],
                    ]
                );
            }
        });

        $this->command->info('Customer profiles seeded successfully!');
    }
}