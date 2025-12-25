<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'google_id' => fake()->optional(0.2)->numerify('##########'),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'province_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'address' => fake()->optional()->streetAddress(),
            'status' => 1,
            'mobile' => fake()->optional()->numerify('0#########'),
            'gender' => fake()->optional()->randomElement([1, 2]),
            'role' => 1,
        ];
    }
}
