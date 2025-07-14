<?php

namespace Database\Seeders;

use App\Models\Factory;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // --- Admin User ---
            $adminUser = User::firstOrCreate(
                ['username' => 'admin'],
                [
                    'firstname' => 'Admin',
                    'lastname' => 'User',
                    'email' => 'admin@lms.com',
                    'employee_id' => 'EMP001',
                    'password' => Hash::make('your-secure-password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $adminUser->assignRole(Role::findByName('Admin'));

            // --- Supplier Users ---
            $supplierUser = User::firstOrCreate(
                ['username' => 'amossupplier'],
                [
                    'firstname' => 'Amos',
                    'lastname' => 'Tindyebwa',
                    'email' => 'supplier1@example.com',
                    'password' => Hash::make('your-secure-password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $supplierUser->assignRole(Role::findByName('Supplier'));

            $supplierUser2 = User::firstOrCreate(
                ['username' => 'mariasupplier'],
                [
                    'firstname' => 'Maria',
                    'lastname' => 'Nankya',
                    'email' => 'supplier2@example.com',
                    'password' => Hash::make('your-secure-password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $supplierUser2->assignRole(Role::findByName('Supplier'));

            // --- Vendor User ---
            $vendorUser = User::firstOrCreate(
                ['username' => 'amosvendor'],
                [
                    'firstname' => 'Amos',
                    'lastname' => 'Tindbwa',
                    'email' => 'vendor@example.com',
                    'password' => Hash::make('your-secure-password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $vendorUser->assignRole(Role::findByName('Vendor'));

            // --- Customer User ---
            $customerUser = User::firstOrCreate(
                ['username' => 'janedoe'],
                [
                    'firstname' => 'Jane',
                    'lastname' => 'Doe',
                    'email' => 'customer@example.com',
                    'password' => Hash::make('your-secure-password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $customerUser->assignRole(Role::findByName('Customer'));

            // Create the customer's profile only if new user
            if ($customerUser->wasRecentlyCreated) {
                Customer::firstOrCreate(
                    ['user_id' => $customerUser->id],
                    [
                        'company_name' => 'Kampala Bar & Grill',
                        'phone_number' => '256700987654',
                    ]
                );
            }

            // --- Liquor Manager User ---
            $managerUser = User::firstOrCreate(
                ['username' => 'sarahmanager'],
                [
                    'firstname' => 'Sarah',
                    'lastname' => 'Manager',
                    'email' => 'manager@lms.com',
                    'employee_id' => 'EMP002',
                    'password' => Hash::make('your-secure-password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $managerUser->assignRole(Role::findByName('Liquor Manager'));

            // --- Manufacturer User ---
            $factory = Factory::first();

            if ($factory) {
                $manufacturerUser = User::firstOrCreate(
                    ['username' => 'johnmaker'],
                    [
                        'firstname' => 'John',
                        'lastname' => 'Maker',
                        'email' => 'manufacturer@lms.com',
                        'employee_id' => 'EMP101',
                        'factory_id' => $factory->id,
                        'password' => Hash::make('your-secure-password'),
                        'email_verified_at' => now(),
                        'remember_token' => Str::random(10),
                    ]
                );
                $manufacturerUser->assignRole(Role::findByName('Manufacturer'));
            } else {
                $this->command->warn('No factories found. Skipping Manufacturer user seeding.');
            }
        });
    }
}
