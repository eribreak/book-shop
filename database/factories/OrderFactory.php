<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employee_code' => fake()->bothify('EMP-#####'),
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'mobile' => fake()->optional()->numerify('0#########'),
            'address' => fake()->optional()->address(),
            'status' => fake()->numberBetween(0, 3),
            'province_id' => null,
            'district_id' => null,
            'ward_id' => null,
        ];
    }
}
