<?php

namespace App\Repositories;

use App\Models\Genre;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use Illuminate\Support\Collection;

class GenreRepository implements GenreRepositoryInterface
{
    public function getGenres(): Collection
    {
        return Genre::all();
    }

    public function storeGenre($data): Genre
    {
        return Genre::create($data);
    }

    public function showGenre(Genre $genre): void
    {
        $genre->load('movies');
    }

    public function updateGenre(Genre $genre, $data): void
    {
        $genre->update($data);
    }

    public function destroyGenre(Genre $genre): void
    {
        $genre->delete();
    }
}
