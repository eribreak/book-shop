<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class FormBookRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'author_ids' => 'required|array',
            'author_ids.*' => 'integer|exists:authors,id',
            'image_url' => 'required|string|url|max:255',
            'published_at' => 'nullable|date',
        ];
    }
}
