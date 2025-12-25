<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::query()->get();
        $books = Book::query()->get();

        if ($orders->isEmpty()) {
            $orders = Order::factory()->count(10)->create();
        }
        if ($books->isEmpty()) {
            $books = Book::factory()->count(30)->create();
        }

        foreach ($orders as $order) {
            $lineCount = fake()->numberBetween(1, 3);
            $pickedBooks = $books->random($lineCount);
            foreach ($pickedBooks as $book) {
                OrderDetail::factory()->create([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'book_name' => $book->name,
                    'quantity' => fake()->numberBetween(1, 3),
                ]);
            }
        }
    }
}
