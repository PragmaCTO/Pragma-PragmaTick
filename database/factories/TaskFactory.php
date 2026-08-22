<?php

namespace Database\Factories;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'milestone_id' => null,
            'assigned_to' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['todo', 'in_progress', 'review', 'done']),
        ];
    }
}
