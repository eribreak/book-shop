<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Collection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::query()->get();
        $authors = Author::query()->get();
        $publishers = Publisher::query()->get();

        if ($categories->isEmpty()) {
            $categories = Category::factory()->count(12)->create();
        }
        if ($authors->isEmpty()) {
            $authors = Author::factory()->count(25)->create();
        }
        if ($publishers->isEmpty()) {
            $publishers = Publisher::factory()->count(6)->create();
        }

        $books = Book::factory()->count(50)->create([
            'publisher_id' => fn() => $publishers->random()->id,
        ]);

        $books->each(function (Book $book) use ($categories, $authors) {
            $categoryIds = $categories->random(fake()->numberBetween(1, 3))->pluck('id')->all();
            $authorIds = $authors->random(fake()->numberBetween(1, 2))->pluck('id')->all();

            $book->categories()->syncWithoutDetaching($categoryIds);
            $book->authors()->syncWithoutDetaching($authorIds);
        });
    }
}
