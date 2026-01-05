<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookListRequest extends FormRequest
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
        return [
            'q' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|array',
            'category_id.*' => 'integer|min:1',
            'author_id' => 'sometimes|array',
            'author_id.*' => 'integer|min:1',
            'publisher_id' => 'sometimes|array',
            'publisher_id.*' => 'integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
