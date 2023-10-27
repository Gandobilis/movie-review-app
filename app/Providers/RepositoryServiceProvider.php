<?php

namespace App\Providers;

use App\Repositories\CollectionRepository;
use App\Repositories\GenreRepository;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\MovieRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\RatingRepository;
use App\Repositories\SubscriptionRepository;
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
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->bind(RatingRepositoryInterface::class, RatingRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
