<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    public function __construct(private GenreRepositoryInterface $genreRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $genres = $this->genreRepository->getGenres();

        return response([
            'genres' => $genres
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreRequest $request): Response
    {
        $data = $request->validated();

        $genre = $this->genreRepository->storeGenre($data);

        return response([
            'genre' => $genre
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre): Response
    {
        $this->genreRepository->showGenre($genre);

        return response([
            'genre' => $genre
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreRequest $request, Genre $genre): Response
    {
        $data = $request->validated();

        $this->genreRepository->updateGenre($genre, $data);

        return response([
            'genre' => $genre
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre): Response
    {
        $this->genreRepository->destroyGenre($genre);

        return response(status: 204);
    }
}
