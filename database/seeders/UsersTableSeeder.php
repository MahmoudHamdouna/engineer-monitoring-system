<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // إذا كنت تستخدم spatie
        ]);

        User::create([
            'name' => 'Team Leader',
            'email' => 'leader@example.com',
            'password' => Hash::make('password'),
            'role' => 'leader',
        ]);

        User::create([
            'name' => 'Engineer 1',
            'email' => 'engineer1@example.com',
            'password' => Hash::make('password'),
            'role' => 'engineer',
        ]);

        // إضافة مهندسين إضافيين
        User::factory(10)->create();
    }
}
