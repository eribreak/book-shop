<?php

namespace App\Repositories\Interface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Book;

interface BookRepositoryInterface
{
    public function getBooks(array $filters, int $perPage = 16): LengthAwarePaginator;

    public function getDetail(int $id): ?Book;

    public function create(array $data): Book;

    public function update(int $id, array $data): ?Book;

    public function delete(int $id): bool;
}
