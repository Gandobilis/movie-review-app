<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use App\Models\Collection;
use Illuminate\Http\Response;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $collections = Collection::with('author:id,name')->paginate(config('paginate.default'));

        return response([
            'collections' => $collections
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollectionRequest $request): Response
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        $collection = Collection::create($data);
        $collection->movies()->attach($data['movie_ids']);

        return response([
            'collection' => $collection
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection): Response
    {
        $collection->load('author', 'movies');

        return response([
            'collection' => $collection
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollectionRequest $request, Collection $collection): Response
    {
        $data = $request->validated();

        $collection->update($data);
        $collection->movies()->sync($data['movie_ids']);

        return response([
            'collection' => $collection
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection): Response
    {
        $collection->delete();

        return response(status: 204);
    }

    public function likedCollections(): Response
    {
        $liked_collections = auth()->user()->likedCollections;

        return response([
            'liked_collections' => $liked_collections
        ]);
    }

    public function toggleCollectionLike(Collection $collection): Response
    {
        auth()->user()->likedCollections()->toggle($collection->id);

        return response([
            'message' => 'like status toggled.'
        ]);
    }
}
