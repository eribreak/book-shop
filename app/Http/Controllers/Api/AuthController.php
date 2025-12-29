<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        return $this->successResponse([
            'user' => $user,
            'token_type' => 'Bearer',
        ], 'Register successfully', 201);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $accessToken = $user->createToken('authToken')->accessToken;

            return $this->successResponse([
                'user' => $user,
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ], 'Log in successfully', 200);
        } else {
            return $this->errorResponse('Login information is invalid', 401);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return $this->successResponse(null, 'Logged out successfully', 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? $this->successResponse(null, 'Reset link sent to your email', 200)
            : $this->errorResponse('Unable to send reset link', 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->tokens()->delete();
            }
        );

        return $status === Password::PasswordReset
            ? $this->successResponse(null, 'Password has been reset successfully', 200)
            : $this->errorResponse('Failed to reset password', 400);
    }
}
