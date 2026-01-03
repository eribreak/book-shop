<?php

namespace App\Repositories\Repository;

use App\Models\Publisher;
use App\Repositories\Interface\PublisherRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PublisherRepository implements PublisherRepositoryInterface
{
    public function getPublishers(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $name = isset($filters['q']) ? trim((string) $filters['q']) : '';

        $query = Publisher::query()
            ->when($name !== '', function ($q) use ($name) {
                $like = '%' . $name . '%';
                $q->where('name', 'like', $like);
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function getDetail(int $id): ?Publisher
    {
        return Publisher::with('books')->find($id);
    }

    public function create(array $data): Publisher
    {
        $publisher = Publisher::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
        ]);

        return $publisher;
    }

    public function update(int $id, array $data): Publisher
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            throw new \Exception('Publisher not found.');
        }

        $publisher->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
        ]);

        return $publisher->load('books');
    }

    public function delete(int $id): bool
    {
        return (bool) Publisher::find($id)?->delete();
    }

    public function bulkDelete(array $ids): int
    {
        return Publisher::whereIn('id', $ids)->whereDoesntHave('books')->delete();
    }
}
