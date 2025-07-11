<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get warehouse IDs by name
        $warehouses = DB::table('warehouses')->pluck('warehouse_id', 'name');

        DB::table('employees')->insert([
            [
                'name'         => 'Alice Namutebi',
                'role'         => 'Inventory Officer',
                'email'        => 'alice@centralwh.com',
                'skillset'     => 'Stock Management, Reporting',
                'warehouse_id' => $warehouses['Central Warehouse'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Michael Owor',
                'role'         => 'Logistics Coordinator',
                'email'        => 'michael@northerndepot.com',
                'skillset'     => 'Coordination, Scheduling',
                'warehouse_id' => $warehouses['Northern Depot'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Grace Kembabazi',
                'role'         => 'Warehouse Assistant',
                'email'        => 'grace@eastdist.com',
                'skillset'     => 'Inventory, Quality Control',
                'warehouse_id' => $warehouses['Eastern Distribution Center'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Peter Mwesigwa',
                'role'         => 'Stock Controller',
                'email'        => 'peter@westhub.com',
                'skillset'     => 'Stock Auditing, Documentation',
                'warehouse_id' => $warehouses['Western Hub'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
