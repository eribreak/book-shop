<?php

namespace App\Http\Requests\BorrowOrder;

use Illuminate\Foundation\Http\FormRequest;

class BorrowOrderListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => 'sometimes|string|max:255',
            'overdue' => 'sometimes|boolean',
        ];
    }
}
