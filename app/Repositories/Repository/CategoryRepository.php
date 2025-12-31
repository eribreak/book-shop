<?php

namespace App\Repositories\Repository;

use App\Models\Category;
use App\Repositories\Interface\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $name = isset($filters['q']) ? trim((string) $filters['q']) : '';

        $query = Category::query()
            ->when($name !== '', function ($q) use ($name) {
                $like = '%' . $name . '%';
                $q->where('name', 'like', $like);
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function getDetail(int $id): ?Category
    {
        return Category::with('books')->find($id);
    }

    public function create(array $data): Category
    {
        $category = Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
            'is_home' => $data['is_home'],
        ]);

        return $category;
    }

    public function update(int $id, array $data): ?Category
    {
        $category = Category::find($id);

        if (!$category) {
            return null;
        }

        return $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
            'is_home' => $data['is_home'],
        ]);
    }

    public function delete(int $id): bool
    {
        return Category::delete($id);
    }

    public function bulkDelete(array $ids): int
    {
        return Category::whereIn('id', $ids)
            ->whereDoesntHave('books')
            ->delete();
    }
}
