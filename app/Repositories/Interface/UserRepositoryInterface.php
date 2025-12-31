<?php

namespace App\Repositories\Interface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;

interface UserRepositoryInterface
{
    public function getUsers(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getDetail(int $id): ?User;

    public function lockUser(int $id): bool;
}
