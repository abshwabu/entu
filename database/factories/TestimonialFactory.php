<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'author_name' => fake()->name(),
            'author_role' => fake()->jobTitle() . ', ' . fake()->company(),
            'quote' => fake()->paragraph(2),
            'is_published' => true,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
