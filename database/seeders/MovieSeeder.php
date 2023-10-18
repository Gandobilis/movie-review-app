<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = Movie::factory()->count(165)->create();

        $genres = Genre::all();

        $movies->each(function ($movie) use ($genres) {
            $randomGenres = $genres->random(rand(1, 5));

            $movie->genres()->attach($randomGenres);
        });
    }
}
