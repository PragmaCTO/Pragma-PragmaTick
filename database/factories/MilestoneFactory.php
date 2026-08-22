<?php

namespace Database\Factories;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Milestone>
 */
class MilestoneFactory extends Factory
{
    protected $model = Milestone::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', 'now');
        $dueDate = fake()->dateTimeBetween('now', '+3 months');

        return [
            'project_id' => Project::factory(),
            'title' => 'Sprint ' . fake()->numberBetween(1, 20) . ': ' . fake()->words(3, true),
            'description' => fake()->sentence(),
            'start_date' => $startDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'status' => fake()->randomElement(['open', 'in_progress', 'completed']),
        ];
    }
}
