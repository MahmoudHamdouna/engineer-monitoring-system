<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            // Dashboard
            'dashboard.view',
            'dashboard.view_team',
            'dashboard.view_system',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.assign_role',
            'users.manage',

            // Teams
            'teams.view',
            'teams.create',
            'teams.update',
            'teams.delete',
            'teams.assign_leader',
            'teams.manage',

            // Projects
            'projects.view',
            'projects.create',
            'projects.update',
            'projects.delete',
            'projects.manage',
            'projects.view_team_only',

            // Tasks
            'tasks.view',
            'tasks.create',
            'tasks.assign',
            'tasks.update',
            'tasks.delete',
            'tasks.change_status',
            'tasks.reprioritize',
            'tasks.comment',
            'tasks.view_team_only',
            'tasks.view_assigned_only',

            // Notifications
            'notifications.view',
            'notifications.mark_as_read',
            'notifications.manage',

            // Reports
            'reports.view',
            'reports.export',
            'reports.view_team',
            'reports.view_system',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Permissions
            'permissions.view',
            'permissions.assign',
            'permissions.manage',

            // Performance
            'performance.view',
            'performance.view_team',
            'performance.view_engineer',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
