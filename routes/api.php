<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('test', function () {
    Mail::to('lasha@gmail.com')->send(new TestMail('Greeting'));
});

Route::middleware('locale')->group(function () {
    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login');

    Route::apiResource('collections', CollectionController::class)
        ->only('index', 'show')
        ->names('collections');

    Route::apiResource('movies', MovieController::class)
        ->only('index', 'show')
        ->names('movies');

    Route::post('subscription/subscribe', [SubscriptionController::class, 'subscribe'])
        ->name('subscribe');
    Route::put('subscription/unsubscribe/{subscription:email}', [SubscriptionController::class, 'unsubscribe'])
        ->name('unsubscribe');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])
            ->name('auth.logout');

        Route::get('profile', [ProfileController::class, 'show'])
            ->name('auth.profile.show');
        Route::put('profile', [ProfileController::class, 'update'])
            ->name('auth.profile.update');

        Route::put('movie/toggle-view/{movie}', [MovieController::class, 'toggleMovieView'])
            ->name('auth.user.movie.toggle.view');
        Route::put('collection/toggle-like/{collection}', [CollectionController::class, 'toggleCollectionLike'])
            ->name('auth.user.collection.toggle.like');

        Route::get('/suggest-movie', [MovieController::class, 'suggestMovie'])->name('suggest-movie');

        Route::apiResource('rating', RatingController::class)
            ->only('store', 'update', 'destroy')
            ->names('auth.rating');

        Route::apiResource('collections', CollectionController::class)
            ->names('auth.collections');

        Route::get('liked-collections', [CollectionController::class, 'likedCollections'])
            ->name('auth.collections.liked');

        Route::apiResource('rate', RatingController::class)
            ->only('crate', 'update', 'destroy')
            ->names('auth.rates');

        Route::apiResource('genres', GenreController::class)
            ->only('index', 'show')
            ->names('auth.genres');

        Route::apiResource('movies', MovieController::class)
            ->names('admin.movies');

        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::apiResource('users', UserController::class)
                ->names('admin.users');

            Route::apiResource('genres', GenreController::class)
                ->only('store', 'update', 'destroy')
                ->names('admin.genres');
        });
    });
});
