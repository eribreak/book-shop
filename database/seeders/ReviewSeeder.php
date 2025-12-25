<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
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

        foreach (range(1, 120) as $i) {
            Review::factory()->create([
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
            ]);
        }
    }
}
