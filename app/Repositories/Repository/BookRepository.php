<?php

namespace App\Repositories\Repository;

use App\Models\Book;
use App\Repositories\Interface\BookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    private function normalizeIds(array $ids): array
    {
        return array_values(array_filter(
            array_map('intval', $ids),
            fn($id) => $id > 0
        ));
    }
}
