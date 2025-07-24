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
            // Liquor Managers
            $this->createUser('Liquor Manager', 'Sarah', 'Manager', 'sarahmanager', 'manager@lms.com', 'EMP002');
            $this->createUser('Liquor Manager', 'James', 'Liquor', 'jamesliquor', 'james.liquor@lms.com', 'EMP102');
            $this->createUser('Liquor Manager', 'Linda', 'Bar', 'lindabar', 'linda.bar@lms.com', 'EMP103');
            $this->createUser('Liquor Manager', 'Brian', 'Spirit', 'brianspirit', 'brian.spirit@lms.com', 'EMP104');

            // Procurement Officers
            $this->createUser('Procurement Officer', 'Peter', 'Procure', 'peterprocure', 'procurement@lms.com', 'EMP003');
            $this->createUser('Procurement Officer', 'Alice', 'Procure', 'aliceprocure', 'alice.procure@lms.com', 'EMP105');
            $this->createUser('Procurement Officer', 'Samuel', 'Procure', 'samuelprocure', 'samuel.procure@lms.com', 'EMP106');
            $this->createUser('Procurement Officer', 'Grace', 'Procure', 'graceprocure', 'grace.procure@lms.com', 'EMP107');

            // Finance
            $this->createUser('Finance', 'Frank', 'Finance', 'frankfinance', 'finance@lms.com', 'EMP004');
            $this->createUser('Finance', 'Helen', 'Money', 'helenmoney', 'helen.money@lms.com', 'EMP108');
            $this->createUser('Finance', 'Paul', 'Cash', 'paulcash', 'paul.cash@lms.com', 'EMP109');
            $this->createUser('Finance', 'Diana', 'Ledger', 'dianaledger', 'diana.ledger@lms.com', 'EMP110');

            // Manufacturers
            $this->createUser('Manufacturer', 'John', 'Maker', 'johnmaker', 'manufacturer@lms.com', 'EMP101');
            $this->createUser('Manufacturer', 'Kevin', 'Brew', 'kevinbrew', 'kevin.brew@lms.com', 'EMP111');
            $this->createUser('Manufacturer', 'Emily', 'Distill', 'emilydistill', 'emily.distill@lms.com', 'EMP112');
            $this->createUser('Manufacturer', 'Oscar', 'Blend', 'oscarblend', 'oscar.blend@lms.com', 'EMP113');

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

            // --- 5. SEED RANDOM MESSAGES BETWEEN USERS ---
            $allUsers = \App\Models\User::all();
            $userCount = $allUsers->count();
            $messages = [
                'Welcome to the system!',
                'Please review your dashboard for updates.',
                'Contact admin if you have any issues.',
                'Your account has been activated.',
                'Let us know if you need any help.',
                'Check out the new features added this week.',
                'Remember to update your profile information.',
                'You have a new notification.',
                'Thank you for being part of our platform.',
                'Your feedback is valuable to us.'
            ];
            $messageCount = 30; // Number of messages to seed
            for ($i = 0; $i < $messageCount; $i++) {
                $sender = $allUsers->random();
                $receiver = $allUsers->where('id', '!=', $sender->id)->random();
                \App\Models\Message::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'message' => $messages[array_rand($messages)],
                ]);
            }
        });

        $this->command->info('All required users, including multiple suppliers and customers, and random messages seeded successfully!');
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