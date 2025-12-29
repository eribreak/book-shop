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
Route::get('/books', [BookController::class, 'getBooks']);

Route::prefix('/admin')->middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/reports/borrows/monthly', [BookController::class, 'borrowedMonthly']);
    Route::get('/reports/borrows/top-users', [BookController::class, 'topBorrowers']);
    Route::get('/reports/borrows/top-books', [BookController::class, 'topBorrowedBooks']);
    Route::get('/reports/books/by-category', [BookController::class, 'booksCountByCategory']);
    Route::get('/reports/wishlists/top-books', [BookController::class, 'topWishlistedBooks']);
});


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
