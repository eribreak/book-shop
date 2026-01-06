<?php

namespace App\Repositories\Interface;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EmployeeRepositoryInterface
{
    public function getEmployees(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getDetail(int $id): ?Employee;

    public function create(array $data): Employee;

    public function update(int $id, array $data): ?Employee;

    public function delete(int $id): bool;

    public function bulkDelete(array $ids): int;

    public function import(array $rows): array;
}
