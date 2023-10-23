<?php

namespace App\Providers;

use App\Repositories\CollectionRepository;
use App\Repositories\GenreRepository;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(GenreRepositoryInterface::class, GenreRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CollectionRepositoryInterface::class, CollectionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
