<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\book>
 */
class bookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
         'title' => fake()->sentence('4'),
         'author' => fake()->name,
         'created_at' => fake()->dateTimeBetween('-2 years'),
         'updated_at' => fake()->dateTimeBetween('created_at', 'now')
        ];
    }
}
