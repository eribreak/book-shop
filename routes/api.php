<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

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
    Route::get('/', [UserController::class, 'getList']);
    Route::get('/{id}', [UserController::class, 'getDetail']);
    Route::post('/{id}/lock', [UserController::class, 'lockUser']);
});

Route::prefix('categories')->middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/', [CategoryController::class, 'getList']);
    Route::get('/{id}', [CategoryController::class, 'getDetail']);
    Route::post('/', [CategoryController::class, 'create']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'delete']);
    Route::post('/bulk-delete', [CategoryController::class, 'bulkDelete']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
