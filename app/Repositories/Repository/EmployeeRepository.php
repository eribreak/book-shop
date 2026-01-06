<?php

namespace App\Repositories\Repository;

use App\Models\Employee;
use App\Repositories\Interface\EmployeeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Helper\Normalize;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function getEmployees(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $keyword = isset($filters['q']) ? trim((string) $filters['q']) : '';

        $query = Employee::query()
            ->when($keyword !== '', function ($q) use ($keyword) {
                $like = '%' . $keyword . '%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('full_name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                });
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function getDetail(int $id): ?Employee
    {
        return Employee::find($id);
    }

    public function create(array $data): Employee
    {
        return Employee::create([
            'employee_code' => $data['employee_code'],
            'email' => $data['email'],
            'full_name' => $data['full_name'],
        ]);
    }

    public function update(int $id, array $data): ?Employee
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return null;
        }

        $employee->update([
            'employee_code' => $data['employee_code'],
            'email' => $data['email'],
            'full_name' => $data['full_name'],
        ]);

        return $employee;
    }

    public function delete(int $id): bool
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return false;
        }

        return (bool) $employee->delete();
    }

    public function bulkDelete(array $ids): int
    {
        $validIds = Normalize::normalizeIds($ids);

        if (empty($validIds)) {
            return 0;
        }

        return Employee::whereIn('id', $validIds)->delete();
    }

    public function import(array $rows): array
    {
        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $employee = Employee::updateOrCreate(
                ['employee_code' => $row['employee_code']],
                [
                    'full_name' => $row['full_name'],
                    'email' => $row['email'],
                ],
            );

            if ($employee->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        return ['created' => $created, 'updated' => $updated];
    }
}
