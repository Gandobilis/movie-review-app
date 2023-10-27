<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Repositories\Interfaces\CollectionRepositoryInterface;

class CollectionRepository implements CollectionRepositoryInterface
{
    public function getCollections()
    {
        return Collection::with('author:id,name')->paginate(config('paginate.default'));
    }

    public function storeCollection($data): Collection
    {
        $data['user_id'] = auth()->id();
        $collection = Collection::create($data);
        $collection->movies()->attach($data['movie_ids']);

        return $collection;
    }

    public function showCollection(Collection $collection): void
    {
        $collection->load('author:id,name', 'movies');
    }

    public function updateCollection(Collection $collection, $data): void
    {
        $collection->update($data);
        $collection->movies()->sync($data['movie_ids']);
    }

    public function destroyCollection(Collection $collection): void
    {
        $collection->delete();
    }

    public function likedCollections()
    {
        return auth()->user()->likedCollections;
    }

    public function toggleCollectionLike(Collection $collection): void
    {
        auth()->user()->likedCollections()->toggle($collection->id);
    }
}
