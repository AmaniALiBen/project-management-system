<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(6,true),
            'description' => fake()->paragraph(),
            'project_id' => Project::factory(),
            'created_by' => fn (array $attributes) => Project::find($attributes['project_id'])->manager_id,
            'assigned_to' =>User::factory()->state(['role' => 'employee']),
            'status' => fake()->randomElement(['to_do','in_progress','in_review','done']),
            'priority' => fake()->randomElement(['low','medium','high','urgent']),
            'due_date' => fake()->dateTimeBetween('now','+3 months')

        ];
    }

  
    public function configure()
    {
        return $this->afterCreating(function (Task $task) {
            $task->project()->members()->syncWithoutDetaching([$task->assigned_to]);
        });
    }
}
