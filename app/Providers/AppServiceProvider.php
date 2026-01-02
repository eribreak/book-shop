<?php

namespace App\Providers;

use App\Repositories\Interface\BookRepositoryInterface;
use App\Repositories\Repository\BookRepository;
use App\Repositories\Interface\AuthorRepositoryInterface;
use App\Repositories\Repository\AuthorRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
