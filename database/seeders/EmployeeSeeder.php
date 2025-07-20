<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\WorkDistribution\Employee;
use App\Models\User;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = DB::table('warehouses')->pluck('warehouse_id', 'name');

        // Ensure the "Employee" role exists
        Role::firstOrCreate(['name' => 'Employee']);

        $employees = [
            [
                'name'         => 'Alice Namutebi',
                'role'         => 'Inventory Officer',
                'email'        => 'alice@centralwh.com',
                'skillset'     => 'Stock Management, Reporting',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'Michael Owor',
                'role'         => 'Logistics Coordinator',
                'email'        => 'michael@northerndepot.com',
                'skillset'     => 'Coordination, Scheduling',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'Grace Kembabazi',
                'role'         => 'Warehouse Assistant',
                'email'        => 'grace@eastdist.com',
                'skillset'     => 'Inventory, Quality Control',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
            [
                'name'         => 'Peter Mwesigwa',
                'role'         => 'Stock Controller',
                'email'        => 'peter@westhub.com',
                'skillset'     => 'Stock Auditing, Documentation',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
            // Casual Employees
            [
                'name'         => 'Brenda Achieng',
                'role'         => 'Casual Employee',
                'email'        => 'brenda@casuals.com',
                'skillset'     => 'Packing, Labelling',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'John Kato',
                'role'         => 'Casual Employee',
                'email'        => 'john@casuals.com',
                'skillset'     => 'Loading, Manual Handling',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
            [
                'name'         => 'Ritah Nankunda',
                'role'         => 'Casual Employee',
                'email'        => 'ritah@casuals.com',
                'skillset'     => 'Sorting, Cleaning',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'Tom Ssekabira',
                'role'         => 'Casual Employee',
                'email'        => 'tom@casuals.com',
                'skillset'     => 'Box Sealing, Labelling',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
            [
                'name'         => 'Sarah Ainebyona',
                'role'         => 'Casual Employee',
                'email'        => 'sarah@casuals.com',
                'skillset'     => 'Packaging, Inventory Support',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
            [
                'name'         => 'Emmanuel Okello',
                'role'         => 'Casual Employee',
                'email'        => 'emmanuel@casuals.com',
                'skillset'     => 'Lifting, Wrapping',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'Josephine Tumusiime',
                'role'         => 'Casual Employee',
                'email'        => 'josephine@casuals.com',
                'skillset'     => 'Data Entry, Filing Vouchers',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'Martin Ssewanyana',
                'role'         => 'Casual Employee',
                'email'        => 'martin@casuals.com',
                'skillset'     => 'Receipt Sorting, Cash Log Handling',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
            [
                'name'         => 'Doreen Nakato',
                'role'         => 'Casual Employee',
                'email'        => 'doreen@casuals.com',
                'skillset'     => 'Invoice Matching, Ledger Support',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'Samuel Mugisha',
                'role'         => 'Casual Employee',
                'email'        => 'samuel@casuals.com',
                'skillset'     => 'Document Verification, Account Filing',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
            [
                'name'         => 'Linda Kabagambe',
                'role'         => 'Casual Employee',
                'email'        => 'linda@casuals.com',
                'skillset'     => 'Payment Vouchers, Excel Entry',
                'warehouse_id' => $warehouses['Kampala Central Warehouse'] ?? null,
            ],
            [
                'name'         => 'George Nsubuga',
                'role'         => 'Casual Employee',
                'email'        => 'george@casuals.com',
                'skillset'     => 'Records Update, Manual Ledger Work',
                'warehouse_id' => $warehouses['Jinja Regional Hub'] ?? null,
            ],
        ];

        foreach ($employees as $employee) {
            if (is_null($employee['warehouse_id'])) {
                $this->command->warn("Missing warehouse for employee: {$employee['name']}");
                continue;
            }

            // Create employee
            Employee::firstOrCreate(
                ['email' => $employee['email']],
                $employee
            );

            // Create matching user for login/roles
            $user = User::firstOrCreate(
                ['email' => $employee['email']],
                [
                    'username'  => explode('@', $employee['email'])[0],
                    'firstname' => explode(' ', $employee['name'])[0],
                    'lastname'  => explode(' ', $employee['name'])[1] ?? '',
                    'password'  => bcrypt('password'),
                ]
            );

            // Assign the Employee role
            if (!$user->hasRole('Employee')) {
                $user->assignRole('Employee');
            }
        }
    }
}
