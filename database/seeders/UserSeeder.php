<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Lasha Gagnidze',
            'email' => 'lashadeveloper@gmail.com',
            'role' => 'admin'
        ]);
        User::factory()->count(12)->create();

        User::all()->each(function ($user) {
            $genres = Genre::inRandomOrder()->limit(rand(1, 13))->get();
            $movies = Movie::inRandomOrder()->limit(rand(1, 33))->get();

            $user->genres()->attach($genres);
            $user->viewedMovies()->attach($movies);
        });

    }
}
