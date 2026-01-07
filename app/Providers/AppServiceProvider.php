<?php

namespace App\Providers;

use App\Repositories\Interface\BookRepositoryInterface;
use App\Repositories\Repository\BookRepository;
use App\Repositories\Interface\ReviewRepositoryInterface;
use App\Repositories\Repository\ReviewRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
