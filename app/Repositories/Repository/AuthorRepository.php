<?php

namespace App\Repositories\Repository;

use App\Models\Author;
use App\Repositories\Interface\AuthorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function getAuthors(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $name = isset($filters['q']) ? trim((string) $filters['q']) : '';

        $query = Author::query()
            ->when($name !== '', function ($q) use ($name) {
                $like = '%' . $name . '%';
                $q->where('name', 'like', $like);
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function getDetail(int $id): ?Author
    {
        return Author::with('books')->find($id);
    }

    public function create(array $data): Author
    {
        $author = Author::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
        ]);

        return $author;
    }

    public function update(int $id, array $data): ?Author
    {
        $author = Author::find($id);

        if (!$author) {
            return null;
        }

        $author->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
        ]);

        return $author->load('books');
    }

    public function delete(int $id): bool
    {
        return (bool) Author::find($id)?->delete();
    }

    public function bulkDelete(array $ids): int
    {
        return Author::whereIn('id', $ids)
            ->whereDoesntHave('books')
            ->delete();
    }
}
