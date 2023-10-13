<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\MovieRequest;
use App\Http\Requests\Movie\MovieUpdateRequest;
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
        $movies = Movie::with('genres:id,name')->paginate(config('paginate.default'));

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
        $movie->genres()->attach($data['genre_ids']);

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
        $movie->load('genres');

        $similar_movies = Movie::whereHas('genres', function ($query) use ($movie) {
            $query->whereIn('genre_id', $movie->genres->pluck('id'));
        })
            ->where('id', '!=', $movie->id)
            ->with('genres')
            ->get();

        return response([
            'movie' => $movie,
            'similar_movies' => $similar_movies
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieUpdateRequest $request, Movie $movie): Response
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'users');
            $this->fileUploadService->deleteFile($movie->image);
        }

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
        $this->fileUploadService->deleteFile($movie->image);
        $movie->delete();

        return response(status: 204);
    }
}
