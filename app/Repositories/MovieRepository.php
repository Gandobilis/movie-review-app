<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\Rating;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Services\FileUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MovieRepository implements MovieRepositoryInterface
{
    public function __construct(private readonly FileUploadService $fileUploadService)
    {
    }

    public function getMovies(Request $request): LengthAwarePaginator
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

        return $query->paginate(perPage: $perPage, page: $page);
    }

    public function storeMovie($data): Movie
    {
        $this->authorize(Movie::class, 'create');

        $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'movies');

        $movie = Movie::create($data);
        $movie->genres()->sync($data['genre_ids']);

        return $movie;
    }

    public function showMovie(Movie $movie): array
    {
        $user = auth()->user();
        $movie->load('genres:id,title');
        $ratings = Rating::with('author:id,name')->where('movie_id', $movie->id)->where('rating', '!=', null)->get();

        $movie['avg_rating'] = number_format($ratings->avg('rating'), 1);

        $similar_movies = $this->getFilteredMovies($movie->genres->pluck('id'), $user?->viewedMovies->pluck('id'));

        return [
            'movie' => $movie,
            'ratings' => $ratings,
            'similar_movies' => $similar_movies
        ];
    }

    public function updateMovie(Movie $movie, $data): void
    {
        $this->authorize(Movie::class, 'update');

        $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'users');
        $this->fileUploadService->deleteFile($movie->image);

        $movie->update($data);
        $movie->genres()->sync($data['genre_ids']);
    }

    public function destroyMovie(Movie $movie): void
    {
        $movie->delete();
        $this->fileUploadService->deleteFile($movie->image);
    }

    public function suggestMovies()
    {
        $user = auth()->user();
        return $this->getFilteredMovies($user->genres->pluck('id'), $user->viewedMovies->pluck('id'));
    }

    public function toggleMovieView(Movie $movie): void
    {
        auth()->user()->viewedMovies()->toggle($movie->id);
    }

    private function getFilteredMovies(Collection $genre_ids, Collection|null $movie_ids)
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
