<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Models\Wiki;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Wiki>
 */
class WikiFactory extends Factory
{
    protected $model = Wiki::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'organization_id' => Organization::factory(),
            'project_id' => null,
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
