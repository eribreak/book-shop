<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'description' => fake()->paragraphs(2, true),
            'slug' => Str::limit($slug . '-' . fake()->unique()->numerify('####'), 255, ''),
            'is_home' => fake()->boolean(20) ? 1 : null,
        ];
    }
}
