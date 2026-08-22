<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $name = fake()->catchPhrase();
        $abbr = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 4));

        return [
            'organization_id' => Organization::factory(),
            'name' => $name,
            'description' => fake()->paragraph(),
            'abbreviation' => $abbr ?: 'PRJ',
        ];
    }
}
