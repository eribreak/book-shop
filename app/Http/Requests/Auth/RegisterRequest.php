<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Họ và tên không được để trống',
            'full_name.string' => 'Họ và tên phải là chuỗi ký tự',
            'full_name.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.string' => 'Email phải là chuỗi ký tự',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 100 ký tự',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ cái và một chữ số',
        ];
    }
}
