<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\UserController;
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

Route::middleware('locale')->group(function () {
    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])
            ->name('auth.logout');

        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::apiResource('users', UserController::class)
                ->names('admin.users');
            Route::put('users/{user}/activate', [UserController::class, 'activate'])
                ->name('admin.users.activate');
            Route::put('users/{user}/deactivate', [UserController::class, 'deactivate'])
                ->name('admin.users.deactivate');

            Route::apiResource('genres', GenreController::class)
                ->only('store', 'update', 'destroy')
                ->names('admin.genres');

            Route::apiResource('movies', MovieController::class)
                ->names('admin.movies');
        });

        Route::apiResource('collections', CollectionController::class)
            ->names('admin.collections');

        Route::apiResource('rate', RateController::class)
            ->only('crate', 'update', 'destroy');

        Route::apiResource('genres', GenreController::class)
            ->only('index', 'show')
            ->names('genres');
    });
});
