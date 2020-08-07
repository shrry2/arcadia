<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'outside access']);
        Permission::create(['name' => 'edit master settings']);
        Permission::create(['name' => 'register users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'edit permissions']);
        Permission::create(['name' => 'read cards']);
        Permission::create(['name' => 'edit cards']);

        // create roles and assign created permissions
        $admin = Role::create(['name' => 'システム管理者'])
            ->givePermissionTo(Permission::all());
    }
}
