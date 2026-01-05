<?php

namespace App\Http\Requests\BorrowOrder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBorrowOrderDetailStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderId' => 'sometimes|exists:orders,id',
            'detailId' => 'sometimes|exists:order_details,id',
            'status' => 'required|integer|in:0,1,2,3',
        ];
    }
}
