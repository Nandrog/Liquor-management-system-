<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\WorkDistribution\Employee; // make sure this matches your actual Employee model namespace

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Use 'warehouse_id' as key since warehouses table uses that as PK
        $warehouses = DB::table('warehouses')->pluck('warehouse_id', 'name');

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
        ];

        foreach ($employees as $employee) {
            Employee::firstOrCreate(
                ['email' => $employee['email']],
                $employee
            );
        }
    }
}
