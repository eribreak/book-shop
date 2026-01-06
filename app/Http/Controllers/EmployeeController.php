<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Employee\FormEmployeeRequest;
use App\Http\Requests\Employee\ImportEmployeeRequest;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Constant\PerPage;
use Illuminate\Http\Response;
use App\Repositories\Interface\EmployeeRepositoryInterface;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository,
    ) {}

    public function getList(Request $request)
    {
        $perPage = (int) $request->query('per_page', PerPage::DEFAULT);
        $filters = [
            'q' => $request->query('q', ''),
        ];

        $employees = $this->employeeRepository->getEmployees($filters, $perPage);
        $employees->appends($request->query());

        return $this->successResponse($employees);
    }

    public function getDetail(int $id)
    {
        $employee = $this->employeeRepository->getDetail($id);

        if (!$employee) {
            return $this->errorResponse('Employee not found.');
        }

        return $this->successResponse($employee);
    }

    public function create(FormEmployeeRequest $request)
    {
        $data = $request->validated();

        $employee = $this->employeeRepository->create($data);

        return $this->successResponse($employee, 'Employee created successfully.', Response::HTTP_CREATED);
    }

    public function update(int $id, FormEmployeeRequest $request)
    {
        $data = $request->validated();

        $employee = $this->employeeRepository->update($id, $data);

        if (!$employee) {
            return $this->errorResponse('Employee not found.');
        }

        return $this->successResponse($employee, 'Employee updated successfully.');
    }

    public function delete(int $id)
    {
        $employee = $this->employeeRepository->getDetail($id);

        if (!$employee) {
            return $this->errorResponse('Employee not found.');
        }

        $deleted = $this->employeeRepository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Failed to delete employee.');
        }

        return $this->successResponse('Employee deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('Invalid employee IDs.');
        }

        $deletedCount = $this->employeeRepository->bulkDelete($ids);

        return $this->successResponse("Deleted {$deletedCount} employees successfully.");
    }

    public function import(ImportEmployeeRequest $request)
    {
        $file = $request->file('file');
        $import = new EmployeesImport($this->employeeRepository);

        Excel::import($import, $file);

        $summary = $import->summary();

        $message = $summary['failed'] === 0
            ? 'Import completed successfully.'
            : 'Import completed with some errors.';

        return $this->successResponse([
            'created' => $summary['created'],
            'updated' => $summary['updated'],
            'failed' => $summary['failed'],
            'total' => $summary['total'],
            'errors' => $summary['errors'],
        ], $message, Response::HTTP_OK);
    }
}
