<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = Genre::all();
        $movies = Movie::factory()->count(500)->create();

        $movies->each(function ($movie) use ($genres) {
            $randomGenres = $genres->random(rand(1, 4));

            $movie->genres()->attach($randomGenres);
        });
    }
}
