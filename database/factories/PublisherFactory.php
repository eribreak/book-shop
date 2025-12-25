<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publisher>
 */
class PublisherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();
        $slug = Str::slug($name);

        return [
            'name' => Str::limit($name, 100, ''),
            'description' => fake()->paragraphs(2, true),
            'slug' => Str::limit($slug . '-' . fake()->unique()->numerify('###'), 100, ''),
        ];
    }
}
