<?php

namespace Database\Seeders;

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

        $users = User::factory()->count(99)->create();
        $genres = Genre::all()->pluck('id');
        $movies = Movie::all()->pluck('id');

        $users->each(function ($user) use ($genres, $movies) {
            $_genres = $genres->random(1, 15);
            $_movies = $movies->random(1, 500);

            $user->genres()->attach($_genres);
            $user->viewedMovies()->attach($_movies);
        });
    }
}
