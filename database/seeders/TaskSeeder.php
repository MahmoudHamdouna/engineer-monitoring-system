<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();
        $users = User::all();

        $priorities = ['urgent', 'normal'];
        $types = ['development', 'fix', 'review'];
        $statuses = ['pending', 'in_progress', 'review', 'done'];

        for ($i = 1; $i <= 50; $i++) {
            $project = $projects->random();
            $assigned_to = $users->random();
            $assigned_by = $users->where('id', '!=', $assigned_to->id)->random(); // اللي عيّنه مختلف عن المسؤول

            $startDate = now()->subDays(rand(0, 20));
            $dueDate = $startDate->copy()->addDays(rand(5, 20));
            $completedAt = null;

            // إذا المهمة مكتملة، نحط completed_at
            $status = $statuses[array_rand($statuses)];
            if ($status === 'done') {
                $completedAt = $dueDate->subDays(rand(0, 3))->addHours(rand(0,5));
            }

            Task::create([
                'title' => 'Task #' . $i . ' for ' . $project->name,
                'description' => 'This is a sample description for task #' . $i,
                'project_id' => $project->id,
                'assigned_to' => $assigned_to->id,
                'assigned_by' => $assigned_by->id,
                'priority' => $priorities[array_rand($priorities)],
                'type' => $types[array_rand($types)],
                'status' => $status,
                'start_date' => $startDate,
                'due_date' => $dueDate,
                'completed_at' => $completedAt,
            ]);
        }
    }
}
