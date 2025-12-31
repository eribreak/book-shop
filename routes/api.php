<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\BookController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);

Route::prefix('books')->middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/', [BookController::class, 'getList']);
    Route::get('/{id}', [BookController::class, 'getDetail']);
    Route::post('/', [BookController::class, 'create']);
    Route::put('/{id}', [BookController::class, 'update']);
    Route::delete('/{id}', [BookController::class, 'delete']);
});

Route::prefix('users')->middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'getList']);
    Route::get('/{id}', [\App\Http\Controllers\UserController::class, 'getDetail']);
    Route::post('/{id}/lock', [\App\Http\Controllers\UserController::class, 'lockUser']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
