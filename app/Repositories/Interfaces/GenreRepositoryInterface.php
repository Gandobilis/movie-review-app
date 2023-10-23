<?php

namespace App\Repositories\Interfaces;

use App\Models\Genre;

interface GenreRepositoryInterface
{
    public function getGenres();

    public function storeGenre($data);

    public function showGenre(Genre $genre);

    public function updateGenre(Genre $genre, $data);

    public function destroyGenre(Genre $genre);
}
