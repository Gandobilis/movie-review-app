<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\MovieUpdateRequest;
use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Services\FileUploadService;
use Illuminate\Http\Response;

class MovieController extends Controller
{
    public function __construct(private readonly FileUploadService $fileUploadService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $movies = Movie::with('genres:id,title')->paginate(config('paginate.default'));

        return response([
            'movies' => $movies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieRequest $request): Response
    {
        $data = $request->validated();

        $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'movies');

        $movie = Movie::create($data);
        $movie->genres()->sync($data['genre_ids']);

        return response([
            'message' => __('movie.success.store'),
            'movie' => $movie
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): Response
    {
        $movie->load('genres:id,title', 'ratings.author:id,name');
        $movie['rating'] = $movie->ratings->avg('rating');

        $similar_movies = $movie->genres()
            ->whereHas('movies', function ($query) use ($movie) {
                $query->where('movies.id', '!=', $movie->id);
            })
            ->with('movies')
            ->paginate(config('paginate.default'));

        return response([
            'movie' => $movie,
            'similar_movies' => $similar_movies
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieRequest $request, Movie $movie): Response
    {
        $data = $request->validated();

        $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'users');
        $this->fileUploadService->deleteFile($movie->image);

        $movie->update($data);
        $movie->genres()->sync($data['genre_ids']);

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
        $movie->delete();
        $this->fileUploadService->deleteFile($movie->image);

        return response(status: 204);
    }

    public function suggestMovie(): Response
    {
        $user = auth()->user();

        $movies = Movie::whereHas('genres', function ($query) use ($user) {
            $query->whereIn('id', $user->genres->pluck('id'));
        })->whereNotIn('id', $user->viewedMovies->pluck('id'))
            ->inRandomOrder()
            ->take(10)
            ->get();

        return response([
            'movies' => $movies
        ]);
    }

    public function toggleMovieView(Movie $movie): Response
    {

        auth()->user()->viewedMovies()->toggle($movie->id);

        return response([
            'message' => 'view status toggled.'
        ]);
    }

    private function getMovies(array $ids)
    {

    }
}
