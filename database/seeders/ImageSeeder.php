<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::query()->get();

        if ($books->isEmpty()) {
            $books = Book::factory()->count(20)->create();
        }

        foreach ($books as $book) {
            Image::factory()->count(fake()->numberBetween(1, 3))->create([
                'book_id' => $book->id,
            ]);
        }
    }
}
