<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $genres = Genre::all();

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

        $genre = Genre::create($data);

        return response([
            'genre' => $genre
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre): Response
    {
        $genre->load('movies');

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

        $genre->update($data);

        return response([
            'genre' => $genre
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre): Response
    {
        $genre->delete();

        return response(status: 204);
    }
}
