<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'Finance',
            'Supplier',
            'Manufacturer',
            'Vendor',
            'Customer',
            'Liquor Manager',
            'Procurement Officer',
            'Admin', // It's good practice to have a super-admin role
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web', // Always specify the guard
            ]);
        }
    }
}
