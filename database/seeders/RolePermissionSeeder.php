<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $leader = Role::firstOrCreate(['name' => 'leader']);
        $engineer = Role::firstOrCreate(['name' => 'engineer']);

        // Permissions
        // Permission::firstOrCreate(['name' => 'create tasks']);
        // Permission::firstOrCreate(['name' => 'assign tasks']);
        // Permission::firstOrCreate(['name' => 'update task status']);

        // Assign Roles to users (example)
        // User::find(1)?->assignRole('admin');
        // User::find(2)?->assignRole('leader');
        // User::find(3)?->assignRole('engineer');
    }
}
