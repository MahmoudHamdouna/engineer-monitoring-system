<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    public function run()
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'admin'
        ]);

        // Engineers
        $engineers = User::factory(30)->create([
            'role' => 'engineer'
        ]);

        // Leaders
        $leaders = User::factory(5)->create([
            'role' => 'leader'
        ]);

        // Teams
        $teams = collect();

        foreach ($leaders as $leader) {

            $team = Team::create([
                'name' => 'Team ' . $leader->id,
                'leader_id' => $leader->id,
                'specialization' => 'Software'
            ]);

            // assign leader team_id
            $leader->update(['team_id' => $team->id]);

            // attach engineers (unique)
            $members = $engineers->splice(0, 6);

            foreach ($members as $eng) {
                $eng->update(['team_id' => $team->id]);
            }

            $teams->push($team);
        }

        // Projects
        $projects = collect();

        foreach ($teams as $team) {

            $projects->push(
                Project::factory(1)->create([
                    'team_id' => $team->id
                ])
            );
        }

        // Tasks
        foreach ($projects->flatten() as $project) {

            $users = User::where('team_id', $project->team_id)->get();

            Task::factory(7)->create([
                'project_id' => $project->id,
                'assigned_to' => $users->random()->id,
                'assigned_by' => $users->where('role', 'leader')->first()->id
            ]);
        }
    }
}
