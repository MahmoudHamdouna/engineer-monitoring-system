<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System;

class SystemsTableSeeder extends Seeder
{
    public function run()
    {
        System::truncate();

        $systems = [
            ['name' => 'Centralized System', 'description' => 'Central control system'],
            ['name' => 'Payment System', 'description' => 'Handles payments & collections'],
            ['name' => 'Documentation System', 'description' => 'Archiving and documentation'],
        ];

        foreach ($systems as $system) {
            System::create($system);
        }
    }
}
