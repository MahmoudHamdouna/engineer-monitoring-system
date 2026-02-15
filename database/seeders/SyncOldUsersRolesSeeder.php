<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SyncOldUsersRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = config('default_permissions'); // الملف اللي جهزناه

        $users = User::all();

        foreach ($users as $user) {
            $roleName = $user->role;

            if (!$roleName) continue;

            // sync role مع Spatie
            $user->syncRoles([$roleName]);

            // sync default permissions من config
            if (isset($defaults[$roleName])) {
                $user->syncPermissions($defaults[$roleName]);
            }

            echo "Updated User: {$user->name} ({$user->role})\n";
        }
    }
}


