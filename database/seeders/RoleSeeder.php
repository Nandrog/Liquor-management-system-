<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    Role::create(['name' => 'Finance']);
        Role::create(['name' => 'Supplier']);
        Role::create(['name' => 'Manufacturer']);
        Role::create(['name' => 'Vendor']);
        Role::create(['name' => 'Customer']);
        Role::create(['name' => 'Liquor Manager']);
        Role::create(['name' => 'Procurement Officer']);
        Role::create(['name' => 'Admin']); // It's good practice to have a super-admin role
    }

    
}
