<?php

namespace App\Imports;

use App\Repositories\Interface\EmployeeRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class EmployeesImport implements OnEachRow, WithHeadingRow
{
    private int $created = 0;
    private int $updated = 0;
    private int $failed = 0;
    private int $total = 0;
    private array $errors = [];

    public function __construct(private readonly EmployeeRepositoryInterface $employeeRepository) {}

    public function onRow(Row $row): void
    {
        $this->total++;

        $data = $this->mapRow($row->toArray());

        if ($this->rowIsEmpty($data)) {
            return;
        }

        $validator = Validator::make($data, [
            'employee_code' => ['required', 'string', 'max:20'],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:100',
                'regex:/^(?:[^@\s]+@kiaisoft\.com\.vn|kiaisoft@gmail\.com|kiaisoft[^@\s]*@gmail\.com)$/i',
            ],
        ], [
            'email.regex' => 'The email is not in the correct format.',
        ]);

        if ($validator->fails()) {
            $this->failed++;
            $this->errors[] = [
                'row' => $row->getIndex(),
                'messages' => $validator->errors()->all(),
            ];
            return;
        }

        $result = $this->employeeRepository->import([$data]);
        $this->created += $result['created'];
        $this->updated += $result['updated'];
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function summary(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'failed' => $this->failed,
            'total' => $this->total,
            'errors' => $this->errors,
        ];
    }

    private function mapRow(array $row): array
    {
        return [
            'employee_code' => $row['employee_code'] ?? '',
            'full_name' => $row['full_name'] ?? '',
            'email' => $row['email'] ?? '',
        ];
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
