<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookName = fake()->sentence(4);

        return [
            'order_id' => Order::factory(),
            'book_id' => Book::factory(),
            'book_name' => $bookName,
            'quantity' => fake()->numberBetween(1, 5),
            'status' => fake()->numberBetween(0, 2),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
