<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['created', 'updated', 'deleted', 'login', 'assigned_role']),
            'description' => fake()->sentence(),
            'subject_type' => null,
            'subject_id' => null,
            'properties' => ['ip' => fake()->ipv4(), 'browser' => fake()->userAgent()],
        ];
    }
}
