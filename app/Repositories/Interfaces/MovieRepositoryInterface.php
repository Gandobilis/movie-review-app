<?php

namespace App\Repositories\Interfaces;

use App\Models\Movie;
use Illuminate\Http\Request;

interface MovieRepositoryInterface
{
    public function getMovies(Request $request);

    public function storeMovie($data);

    public function showMovie(Movie $movie);

    public function updateMovie(Movie $movie, $data);

    public function destroyMovie(Movie $movie);

    public function suggestMovies();

    public function toggleMovieView(Movie $movie);
}
