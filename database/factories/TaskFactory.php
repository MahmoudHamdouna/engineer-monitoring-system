<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    // اختر مشروع عشوائي
    $project = Project::inRandomOrder()->first();

    // جيب أعضاء نفس الفريق
    $teamUsers = User::where('team_id', $project->team_id)->get();

    // اختار مهندس عشوائي من الفريق
    $assignee = $teamUsers->where('role','engineer')->random();

    // اختار القائد
    $leader = $teamUsers->where('role','leader')->first();

    $status = fake()->randomElement(['pending','in_progress','review','done']);

    return [
        'title' => fake()->sentence(4),
        'description' => fake()->paragraph(),

        'project_id' => $project->id,
        'assigned_to' => $assignee->id,
        'assigned_by' => $leader->id,

        'priority' => fake()->randomElement(['urgent','normal']),
        'type' => fake()->randomElement(['development','fix','review']),
        'status' => $status,

        'start_date' => now()->subDays(rand(1,10)),
        'due_date' => now()->addDays(rand(5,20)),

        'completed_at' => $status === 'done' ? now() : null,
    ];
}
}


