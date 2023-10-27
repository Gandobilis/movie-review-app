<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MovieController extends Controller
{
    public function __construct(private readonly MovieRepositoryInterface $movieRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return response([
            'movies' => $this->movieRepository->getMovies($request)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieRequest $request): Response
    {
        return response([
            'message' => __('movie.success.store'),
            'movie' => $this->movieRepository->showMovie($request->validated())
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): Response
    {
        return response($this->movieRepository->showMovie($movie));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieRequest $request, Movie $movie): Response
    {
        $this->movieRepository->updateMovie($movie, $request->validated());

        return response([
            'message' => __('movie.success.update'),
            'movie' => $movie
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): Response
    {
        $this->movieRepository->destroyMovie($movie);

        return response(status: 204);
    }

    public function suggestMovie(): Response
    {
        return response([
            'movies' => $this->movieRepository->suggestMovies()
        ]);
    }

    public function toggleMovieView(Movie $movie): Response
    {
        $this->movieRepository->toggleMovieView($movie);

        return response([
            'message' => 'view status toggled.'
        ]);
    }
}
