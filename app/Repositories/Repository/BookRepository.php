<?php

namespace App\Repositories\Repository;

use App\Models\Book;
use App\Repositories\Interface\BookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BookRepository implements BookRepositoryInterface
{
    public function getBooks(array $filters, int $perPage = 16): LengthAwarePaginator
    {
        $name = isset($filters['q']) ? trim((string) $filters['q']) : '';

        $categoryIds = $this->normalizeIds($filters['category_id'] ?? []);
        $authorIds = $this->normalizeIds($filters['author_id'] ?? []);
        $publisherIds = $this->normalizeIds($filters['publisher_id'] ?? []);

        $query = Book::query()
            ->with(['categories', 'authors', 'publisher'])
            ->when($name !== '', function ($q) use ($name) {
                $q->where(function ($qq) use ($name) {
                    $like = '%' . $name . '%';
                    $qq->where('name', 'like', $like)
                        ->orWhereHas('categories', function ($qCategories) use ($like) {
                            $qCategories->where('name', 'like', $like);
                        })
                        ->orWhereHas('authors', function ($qAuthors) use ($like) {
                            $qAuthors->where('name', 'like', $like);
                        })
                        ->orWhereHas('publisher', function ($qPublisher) use ($like) {
                            $qPublisher->where('name', 'like', $like);
                        });
                });
            })
            ->when(!empty($publisherIds), function ($q) use ($publisherIds) {
                $q->whereIn('publisher_id', $publisherIds);
            })
            ->when(!empty($categoryIds), function ($q) use ($categoryIds) {
                $q->whereHas('categories', function ($qq) use ($categoryIds) {
                    $qq->whereIn('categories.id', $categoryIds);
                });
            })
            ->when(!empty($authorIds), function ($q) use ($authorIds) {
                $q->whereHas('authors', function ($qq) use ($authorIds) {
                    $qq->whereIn('authors.id', $authorIds);
                });
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function getDetail(int $id): ?Book
    {
        return Book::with(['categories', 'authors', 'publisher'])->find($id);
    }

    public function create(array $data): Book
    {
        $book = Book::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'publisher_id' => $data['publisher_id'],
            'quantity' => $data['quantity'],
            'image_url' => $data['image_url'],
            'published_at' => $data['published_at'] ?? null,
        ]);

        $book->categories()->sync($this->normalizeIds($data['category_ids']));
        $book->authors()->sync($this->normalizeIds($data['author_ids']));
        return $book->load(['categories', 'authors', 'publisher']);
    }

    public function update(int $id, array $data): ?Book
    {
        $book = Book::find($id);
        if ($book) {
            $book->update([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'publisher_id' => $data['publisher_id'],
                'quantity' => $data['quantity'],
                'image_url' => $data['image_url'],
                'published_at' => $data['published_at'] ?? null,
            ]);
            $book->categories()->sync($this->normalizeIds($data['category_ids']));
            $book->authors()->sync($this->normalizeIds($data['author_ids']));
        }
        return $book->load(['categories', 'authors', 'publisher']);
    }
    public function delete(int $id): bool
    {
        $book = Book::find($id);
        if ($book) {
            return $book->delete();
        }
        return false;
    }
    private function normalizeIds(array $ids): array
    {
        return array_values(array_filter(
            array_map('intval', $ids),
            fn($id) => $id > 0
        ));
    }
}
