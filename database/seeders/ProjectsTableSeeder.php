<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Team;
use App\Models\System;

class ProjectsTableSeeder extends Seeder
{
    public function run()
    {
        Project::truncate();

        $team = Team::first();
        $system = System::first();

        Project::create([
            'name' => 'Website Redesign',
            'description' => 'Full frontend and backend redesign',
            'team_id' => $team->id,
            'system_id' => $system->id,
            'start_date' => now(),
            'end_date' => now()->addWeeks(4),
        ]);
    }
}
