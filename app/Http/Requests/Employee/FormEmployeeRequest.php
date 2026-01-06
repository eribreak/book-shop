<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'employee_code' => [
                'required',
                'string',
                'max:20',
                'unique:employees,employee_code,' . $id,
            ],

            'email' => [
                'required',
                'string',
                'max:100',
                'email',
                'unique:employees,email,' . $id,
                'regex:/^(?:[^@\s]+@kiaisoft\.com\.vn|[^@\s]*kiaisoft@gmail\.com|kiaisoft[^@\s]*@gmail\.com)$/i',
            ],

            'full_name' => 'required|string|max:100',
        ];
    }
}
