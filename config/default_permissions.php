<?php
// config/default_permissions.php
return [
    // Admin: كل شيء تقريبًا
    'admin' => [
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

        // Roles & Permissions
        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',
        'permissions.view',
        'permissions.assign',
        'permissions.manage',

        // Performance / Analytics
        'performance.view',
        'performance.view_team',
        'performance.view_engineer',
    ],

    // Leader: يركز على فريقه والمهام
    'leader' => [
        // Dashboard
        'dashboard.view',
        'dashboard.view_team',

        // Projects
        'projects.view_team_only',
        'projects.update',

        // Tasks
        'tasks.view_team_only',
        'tasks.create',
        'tasks.assign',
        'tasks.update',
        'tasks.change_status',
        'tasks.reprioritize',
        'tasks.comment',

        // Notifications
        'notifications.view',

        // Reports / Analytics
        'reports.view_team',
        'performance.view_team',
    ],

    // Engineer: يركز على المهام المكلف بها
    'engineer' => [
        // Dashboard
        'dashboard.view',

        // Tasks
        'tasks.view_assigned_only',
        'tasks.change_status',
        'tasks.comment',

        // Notifications
        'notifications.view',

        // Performance
        'performance.view_engineer',
    ],
];
