<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
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
        $status=fake()->randomElement(['planned', 'active', 'on_hold', 'completed', 'cancelled']);

        [$start_date,$due_date]=match($status){
            'planned'=>[
                fake()->dateTimeBetween('+1 week','+1 month'),
                fake()->dateTimeBetween('+2 months','+5 months')
            ],

            'completed'=>[
                fake()->dateTimeBetween('-5 months','-2 weeks'),
                fake()->dateTimeBetween('-1 week','now')
            ],
            default=>[
                fake()->dateTimeBetween('-5 months','-1 day'),
                fake()->dateTimeBetween('+1 month','+4 months')
            ]
        };

        
        return [
            'name' => fake()->words(3,true),
            'description' => fake()->paragraph(),
            'manager_id' => User::factory()->state(['role'=>'manager']),
            'status' => $status,
            'start_date' => $start_date ,
            'due_date' => $due_date

        ];
    }
}
