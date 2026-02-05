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
    Route::get('/', [BookController::class, 'getList'])->name('books.list');
    Route::get('/{id}', [BookController::class, 'getDetail'])->name('books.detail');
    Route::post('/', [BookController::class, 'create'])->name('books.create');
    Route::put('/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/{id}', [BookController::class, 'delete'])->name('books.delete');
});

Route::prefix('authors')->middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\AuthorController::class, 'getList']);
    Route::get('/{id}', [\App\Http\Controllers\AuthorController::class, 'getDetail']);
    Route::post('/', [\App\Http\Controllers\AuthorController::class, 'create']);
    Route::put('/{id}', [\App\Http\Controllers\AuthorController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\AuthorController::class, 'delete']);
    Route::post('/bulk-delete', [\App\Http\Controllers\AuthorController::class, 'bulkDelete']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
