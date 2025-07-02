<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'view stock levels']);

        Role::create(['name' => 'Finance']);
        Role::create(['name' => 'Supplier']);
        Role::create(['name' => 'Manufacturer']);
        Role::create(['name' => 'Vendor']);
        Role::create(['name' => 'Customer']);
        $managerRole=Role::create(['name' => 'Liquor Manager']);
        $officerRole=Role::create(['name' => 'Procurement Officer']);
        Role::create(['name' => 'Admin']); // It's good practice to have a super-admin role

        $managerRole->givePermissionTo('view stock levels');
        $officerRole->givePermissionTo('view stock levels');
    }

    
}
