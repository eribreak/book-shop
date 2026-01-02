<?php

namespace App\Repositories\Interface;

use App\Models\Publisher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PublisherRepositoryInterface
{
    public function getPublishers(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getDetail(int $id): ?Publisher;

    public function create(array $data): Publisher;

    public function update(int $id, array $data): Publisher;

    public function delete(int $id): bool;

    public function bulkDelete(array $ids): int;
}
