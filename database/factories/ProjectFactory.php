<?php

namespace Database\Factories;

use App\Models\Category;
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
        return [
            'category_id' => Category::factory(),
            'title' => fake()->unique()->words(3, true),
            'client' => fake()->company(),
            'year' => (string) fake()->numberBetween(2022, 2026),
            'summary' => fake()->sentence(12),
            'description' => '<p>' . implode('</p><p>', fake()->paragraphs(3)) . '</p>',
            'tools_used' => fake()->randomElements([
                'Adobe Illustrator',
                'Adobe Photoshop',
                'Figma',
                'InDesign',
                'After Effects',
                'Procreate',
                'Blender',
            ], fake()->numberBetween(2, 4)),
            'project_url' => fake()->boolean(70) ? fake()->url() : null,
            'is_featured' => fake()->boolean(40),
            'is_published' => true,
            'published_at' => now()->subDays(fake()->numberBetween(1, 180)),
            'sort_order' => fake()->numberBetween(1, 50),
            'seo_title' => fake()->sentence(6),
            'seo_description' => fake()->sentence(15),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
