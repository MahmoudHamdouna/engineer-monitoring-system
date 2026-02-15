<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
        'description' => fake()->paragraph(),
        'team_id' => Team::inRandomOrder()->first()->id,
        'start_date' => now(),
        'end_date' => now()->addMonths(3),
        'status' => fake()->randomElement(['planning','active','completed'])
    
        ];
    }
}
