<?php

namespace Database\Seeders;

use App\Models\Factory;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use a transaction to ensure all users and profiles are created together
        DB::transaction(function () {
            // --- Create Admin User ---
            $adminUser = User::factory()->create([
                'firstname' => 'Admin',
                'lastname' => 'User',
                'username' => 'admin',
                'email' => 'admin@lms.com',
                'employee_id' => 'EMP001'
            ]);
            $adminUser->assignRole(Role::findByName('Admin'));

            // --- Create Supplier User and Profile ---
            $supplierUser = User::factory()->create([
                'firstname' => 'Amos',
                'lastname' => 'Tindyebwa',
                'username' => 'amossupplier',
                'email' => 'supplier1@example.com',
            ]);
            $supplierUser->assignRole(Role::findByName('Supplier'));
            // Create the supplier's business profile
            Supplier::create([
                'user_id' => $supplierUser->id,
                'company_name' => 'Tindyebwa Farms Ltd.',
                'contact_person' => 'Amos Tindyebwa',
                'phone_number' => '256777123456',
            ]);

            $supplierUser2 = User::factory()->create([
    'firstname' => 'Maria',
    'lastname' => 'Nankya',
    'username' => 'mariasupplier',
    'email' => 'supplier2@example.com',
]);
$supplierUser2->assignRole(Role::findByName('Supplier'));
Supplier::create([
    'user_id' => $supplierUser2->id,
    'company_name' => 'Nankya Packaging Solutions',
    'contact_person' => 'Maria Nankya',
    'phone_number' => '256755987654',
]);

            $vendorUser = User::factory()->create([
                'firstname' => 'Amos',
                'lastname' => 'Tindbwa',
                'username' => 'amosvendor',
                'email' => 'vendor@example.com',
            ]);     
            $vendorUser->assignRole(Role::findByName('Vendor'));
            // Create the supplier's business profile
            Supplier::create([
                'user_id' => $vendorUser->id,
                'company_name' => 'Tindyebwa Retails Ltd.',
                'contact_person' => 'Amos Tindbwa',
                'phone_number' => '256777123456',
            ]);

            // --- Create Customer User and Profile ---
            $customerUser = User::factory()->create([
                'firstname' => 'Jane',
                'lastname' => 'Doe',
                'username' => 'janedoe',
                'email' => 'customer@example.com',
            ]);
            $customerUser->assignRole(Role::findByName('Customer'));
            // Create the customer's business profile
            Customer::create([
                'user_id' => $customerUser->id,
                'company_name' => 'Kampala Bar & Grill',
                'phone_number' => '256700987654',
            ]);

            // --- Create a Liquor Manager User ---
            $managerUser = User::factory()->create([
                'firstname' => 'Sarah',
                'lastname' => 'Manager',
                'username' => 'sarahmanager',
                'email' => 'manager@lms.com',
                'employee_id' => 'EMP002'
            ]);
            $managerUser->assignRole(Role::findByName('Liquor Manager'));

                        // --- NEW: Create a Manufacturer User and assign to a Factory ---
            // 1. Find the first factory that was created by the FactorySeeder.
            $factory = Factory::first();

            if ($factory) {
                // 2. Create the manufacturer user and pass the factory_id directly.
                $manufacturerUser = User::factory()->create([
                    'firstname' => 'John',
                    'lastname' => 'Maker',
                    'username' => 'johnmaker',
                    'email' => 'manufacturer@lms.com',
                    'employee_id' => 'EMP101',
                    'factory_id' => $factory->id, // Assign the factory here
                ]);
                $manufacturerUser->assignRole(Role::findByName('Manufacturer'));
            } else {
                $this->command->warn('No factories found. Skipping Manufacturer user seeding.');
            }
        });
    }
}