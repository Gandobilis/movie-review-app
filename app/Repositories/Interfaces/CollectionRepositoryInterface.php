<?php

namespace App\Repositories\Interfaces;

use App\Models\Collection;

interface CollectionRepositoryInterface
{
    public function getCollections();

    public function storeCollection($data);

    public function showCollection(Collection $collection);

    public function updateCollection(Collection $collection, $data);

    public function destroyCollection(Collection $collection);

    public function likedCollections();

    public function toggleCollectionLike(Collection $collection);
}
