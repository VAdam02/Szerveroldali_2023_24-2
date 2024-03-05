<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(rand(5, 20), true),
            'content' => fake()->paragraphs(rand(3, 6), true),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'public' => fake()->boolean(90)
        ];
    }
}
