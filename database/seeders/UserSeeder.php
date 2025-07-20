<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's users table.
     */
    public function run(): void
    {
        DB::transaction(function () {
            
            // --- 1. SEED SINGLE USERS FOR EACH APPLICATION ROLE ---
            $this->createUser('Admin', 'Admin', 'User', 'admin', 'admin@lms.com', 'EMP001');
            $this->createUser('Liquor Manager', 'Sarah', 'Manager', 'sarahmanager', 'manager@lms.com', 'EMP002');
            $this->createUser('Procurement Officer', 'Peter', 'Procure', 'peterprocure', 'procurement@lms.com', 'EMP003');
            $this->createUser('Finance', 'Frank', 'Finance', 'frankfinance', 'finance@lms.com', 'EMP004');
            $this->createUser('Manufacturer', 'John', 'Maker', 'johnmaker', 'manufacturer@lms.com', 'EMP101');
            
            
            // --- 1b. SEED MULTIPLE VENDOR USERS ---
            $vendors = [
                ['name' => 'Capital Shoppers Ntinda', 'contact' => '00800097656', 'email' => 'capitalntinda@vendor.com', 'username' => 'capitalntinda'],
                ['name' => 'Shoprite Lugogo', 'contact' => '00803097656', 'email' => 'shopritelugogo@vendor.com', 'username' => 'shopritelugogo'],
                ['name' => 'Mega Standard Supermarket', 'contact' => '00870097656', 'email' => 'megastandard@vendor.com', 'username' => 'megastandard'],
                ['name' => 'Quality Supermarket', 'contact' => '00860097656', 'email' => 'qualitysupermarket@vendor.com', 'username' => 'qualitysupermarket'],
                ['name' => 'Carrefour Oasis Mall', 'contact' => '00809097656', 'email' => 'carrefouroasis@vendor.com', 'username' => 'carrefouroasis'],
            ];
            foreach ($vendors as $data) {
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $data['email']],
                    [
                        'firstname' => $data['name'],
                        'lastname' => 'Vendor',
                        'username' => $data['username'],
                        'email' => $data['email'],
                        'password' => Hash::make('password'),
                        'email_verified_at' => now(),
                    ]
                );
                $user->assignRole('Vendor');
                // Ensure user is saved and has an id
                if (!$user->id) { $user->save(); }
                \App\Models\Vendor::firstOrCreate(
                    ['name' => $data['name'], 'contact' => $data['contact']],
                    ['user_id' => $user->id]
                );
            }
    
            // --- 2. SEED MULTIPLE SUPPLIER USERS ---
            $this->createUser('Supplier', 'Amos', 'Tindyebwa', 'amossupplier', 'supplier1@example.com');
            $this->createUser('Supplier', 'Maria', 'Nankya', 'mariasupplier', 'supplier2@example.com');
            $this->createUser('Supplier', 'David', 'Okello', 'davidsupplier', 'supplier3@example.com');
            $this->createUser('Supplier', 'Esther', 'Mbabazi', 'esthersupplier', 'supplier4@example.com');
            $this->createUser('Supplier', 'Peter', 'Muwanga', 'petersupplier', 'supplier5@example.com');
            $this->createUser('Supplier', 'Sarah', 'Achen', 'sarahsupplier', 'supplier6@example.com');
            $this->createUser('Supplier','Chemco','Uganda', 'chemcosupplier',  'supplier7@example.com'); 
            $this->createUser( 'Supplier', 'Botanicals',  'Inc',  'botanicalssupplier', 'supplier8@example.com');
    
            // --- 3. SEED MULTIPLE CUSTOMER USERS ---
            $customers = [
                ['firstname' => 'Jane', 'lastname' => 'Doe', 'username' => 'janedoe', 'email' => 'customer1@example.com'],
                ['firstname' => 'John', 'lastname' => 'Muwesi', 'username' => 'johnmuwesi', 'email' => 'customer2@example.com'],
                ['firstname' => 'Brenda', 'lastname' => 'Nakato', 'username' => 'brendanakato', 'email' => 'customer3@example.com'],
                ['firstname' => 'David', 'lastname' => 'Okello', 'username' => 'davidokello', 'email' => 'customer4@example.com'],
            ];

            foreach ($customers as $customerData) {
                $this->createUser(
                    'Customer',
                    $customerData['firstname'],
                    $customerData['lastname'],
                    $customerData['username'],
                    $customerData['email']
                    // employeeId is correctly omitted here
                );
            }

            // --- 4. ASSIGN MANUFACTURERS TO FACTORIES ---
            $this->assignManufacturersToFactories();

        });

        $this->command->info('All required users, including multiple suppliers and customers, seeded successfully!');
    }

    /**
     * A simple helper function to create a user and assign a role.
     */
    private function createUser(string $role, string $firstname, string $lastname, string $username, string $email, ?string $employeeId = null): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make('password'),
                'employee_id' => $employeeId,
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole($role);
    }

    /**
     * Assign each manufacturer user to a random factory.
     */
    private function assignManufacturersToFactories(): void
    {
        $factories = \App\Models\Factory::pluck('id')->toArray();
        $manufacturers = \App\Models\User::role('Manufacturer')->get();

        foreach ($manufacturers as $manufacturer) {
            $manufacturer->factory_id = $factories[array_rand($factories)];
            $manufacturer->save();
        }
    }
}