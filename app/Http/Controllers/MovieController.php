<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Models\Rating;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
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
    public function index(Request $request): Response
    {
        $query = Movie::with('genres:id,title');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('order_by')) {
            $orderBy = $request->input('order_by');

            if (in_array('latest', $orderBy)) {
                $query->orderBy('movies.created_at', 'desc');
            }

            if (in_array('rating', $orderBy)) {
                $query->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
                    ->groupBy('movies.id')
                    ->select('movies.*')
                    ->selectRaw('ROUND(AVG(COALESCE(ratings.rating, 0)), 1) as avg_rating')
                    ->orderBy('avg_rating', 'desc');
            }
        }

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', config('paginate.default'));

        $movies = $query->paginate(perPage: $perPage, page: $page);

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
        $ratings = Rating::with('author:id,name')->where('movie_id', $movie->id)->where('rating', '!=', null)->get();

        $movie['avg_rating'] = number_format($ratings->avg('rating'), 1);

        $similar_movies = $this->getMovies($movie->genres->pluck('id'), $user?->viewedMovies->pluck('id'));

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

    private function getMovies(Collection $genre_ids, Collection|null $movie_ids)
    {
        if ($movie_ids === null) {
            $movie_ids = collect();
        }

        return Movie::whereHas('genres', function ($query) use ($genre_ids, $movie_ids) {
            $query->whereIn('id', $genre_ids);
        })->whereNotIn('id', $movie_ids)
            ->with('genres:id,title')
            ->inRandomOrder()
            ->take(10)
            ->get();
    }
}
