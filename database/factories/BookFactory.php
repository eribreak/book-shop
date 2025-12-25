<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(4);
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'slug' => Str::limit($slug . '-' . fake()->unique()->numerify('####'), 255, ''),
            'short_description' => fake()->sentence(12),
            'description' => fake()->paragraphs(4, true),
            'image_url' => fake()->imageUrl(640, 480, 'books', true),
            'quantity' => fake()->numberBetween(0, 200),
            'published_at' => fake()->optional(0.7)->dateTimeBetween('-2 years', 'now'),
            'publisher_id' => Publisher::factory(),
        ];
    }
}
