<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TasksTableSeeder extends Seeder
{
    public function run()
    {
        Task::truncate();

        $project = Project::first();
        $engineer = User::where('role', 'engineer')->first();

        Task::create([
            'title' => 'Setup frontend skeleton',
            'description' => 'Initial setup of Vue.js project',
            'project_id' => $project->id,
            'assigned_to' => $engineer->id,
            'assigned_by' => User::where('role','leader')->first()->id,
            'priority' => 'normal',
            'type' => 'development',
            'status' => 'pending',
            'start_date' => now(),
            'due_date' => now()->addDays(5),
        ]);
    }
}
