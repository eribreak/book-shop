<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();
        $books = Book::query()->get();

        if ($users->isEmpty()) {
            $users = User::factory()->count(20)->create();
        }
        if ($books->isEmpty()) {
            $books = Book::factory()->count(30)->create();
        }

        foreach ($users as $user) {
            $items = fake()->numberBetween(0, 3);
            if ($items === 0) {
                continue;
            }

            $pickedBooks = $books->random($items);
            foreach ($pickedBooks as $book) {
                Cart::factory()->create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                ]);
            }
        }
    }
}
