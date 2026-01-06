<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EmployeeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);

Route::prefix('books')->middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/', [BookController::class, 'getList'])->name('books.list');
    Route::get('/{id}', [BookController::class, 'getDetail'])->name('books.detail');
    Route::post('/', [BookController::class, 'create'])->name('books.create');
    Route::put('/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/{id}', [BookController::class, 'delete'])->name('books.delete');
});

Route::prefix('employees')->middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/', [EmployeeController::class, 'getList']);
    Route::get('/{id}', [EmployeeController::class, 'getDetail']);
    Route::post('/', [EmployeeController::class, 'create']);
    Route::put('/{id}', [EmployeeController::class, 'update']);
    Route::delete('/{id}', [EmployeeController::class, 'delete']);
    Route::post('/import', [EmployeeController::class, 'import']);
    Route::post('/bulk-delete', [EmployeeController::class, 'bulkDelete']);
});


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
