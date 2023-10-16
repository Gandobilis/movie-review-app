<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Models\Rating;
use App\Services\FileUploadService;
use Illuminate\Support\Collection;
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
        $user = auth()->user();
        $movie->load('genres:id,title');
        $ratings = Rating::where('movie_id', $movie->id)->where('rating', '!=', null)->get();

        $movie['avg_rating'] = $ratings->avg('rating');

        $similar_movies = $this->getMovies($movie->genres->pluck('id'), $user->viewedMovies->pluck('id'));

        return response([
            'movie' => $movie,
            'ratings' => $ratings,
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
        $movies = $this->getMovies($user->genres->pluck('id'), $user->viewedMovies->pluck('id'));

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

    private function getMovies(Collection $genre_ids, Collection $movie_ids)
    {
        return Movie::whereHas('genres', function ($query) use ($genre_ids, $movie_ids) {
            $query->whereIn('id', $genre_ids);
        })->whereNotIn('id', $movie_ids)
            ->inRandomOrder()
            ->take(10)
            ->get();
    }
}
