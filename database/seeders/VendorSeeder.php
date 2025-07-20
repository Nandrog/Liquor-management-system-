<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This will populate the 'vendors' table with a predefined list.
     */
    public function run(): void
    {
        // Using firstOrCreate ensures that we don't create duplicate vendors
        // if the seeder is run multiple times. It checks if a vendor with the
        // given name exists, and only creates it if it doesn't.
        Vendor::firstOrCreate(['name' => 'Capital Shoppers Ntinda',
                            'contact' => '00800097656'
    ]);
        Vendor::firstOrCreate(['name' => 'Shoprite Lugogo',
                                'contact' => '00803097656'
    ]);
        Vendor::firstOrCreate(['name' => 'Mega Standard Supermarket',
                                'contact' => '00870097656'
                            ]);
        Vendor::firstOrCreate(['name' => 'Quality Supermarket',
                                'contact' => '00860097656'
                            ]);
        Vendor::firstOrCreate(['name' => 'Carrefour Oasis Mall',
                                'contact' => '00809097656'
    ]);

        // Retrieve or create a user for the vendor
        $user1 = \App\Models\User::firstOrCreate([
            'email' => 'amos@example.com'
        ], [
            'name' => 'Amos Tindbwa',
            'password' => bcrypt('password123')
        ]);

        Vendor::create([
                'user_id'      => $user1->id, // <-- CRITICAL: Use the new user's ID
                'name'         => 'amos', // Or 'Amos Tindbwa'
                'company_name' => 'Amos Fine Spirits',
                'contact'      => '256700987654',
            ]);
            
        // This command will output a message to your console when seeding is successful.
        $this->command->info('Vendors table seeded successfully!');
    }
}
