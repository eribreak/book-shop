<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $request->validated();

            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Đăng ký thành công',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đăng ký thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate(
                [
                    'email' => 'required|email',
                    'password' => 'required|min:8',
                ],
                [
                    'email.required' => 'Email không được để trống',
                    'email.email' => 'Email không đúng định dạng',
                    'password.required' => 'Mật khẩu không được để trống',
                    'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                ]
            );

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Email hoặc mật khẩu không đúng'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Đăng nhập thành công',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đăng nhập thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {

            if (! $request->user()) {
                return response()->json([
                    'message' => 'Người dùng chưa đăng nhập',
                ], 401);
            }

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Đăng xuất thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đăng xuất thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
