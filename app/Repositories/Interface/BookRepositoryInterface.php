<?php

namespace App\Repositories\Interface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function getBooks(array $filters, int $perPage = 16): LengthAwarePaginator;
}
