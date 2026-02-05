<?php

namespace App\Repositories\Interface;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AuthorRepositoryInterface
{
    public function getAuthors(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getDetail(int $id): ?Author;

    public function create(array $data): Author;

    public function update(int $id, array $data): ?Author;

    public function delete(int $id): bool;

    public function bulkDelete(array $ids): int;
}
