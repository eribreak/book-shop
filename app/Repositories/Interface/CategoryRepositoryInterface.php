<?php

namespace App\Repositories\Interface;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getCategories(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getDetail(int $id): ?Category;

    public function create(array $data): Category;

    public function update(int $id, array $data): ?Category;

    public function delete(int $id): bool;

    public function bulkDelete(array $ids): int;
}
