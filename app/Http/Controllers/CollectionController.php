<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use App\Models\Collection;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use Illuminate\Http\Response;

class CollectionController extends Controller
{
    public function __construct(private CollectionRepositoryInterface $collectionRepository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $collections = $this->collectionRepository->getCollections();

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

        $collection = $this->collectionRepository->storeCollection($data);

        return response([
            'collection' => $collection
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection): Response
    {
        $this->collectionRepository->showCollection($collection);

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

        $this->collectionRepository->updateCollection($collection, $data);

        return response([
            'collection' => $collection
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection): Response
    {
        $this->collectionRepository->destroyCollection($collection);

        return response(status: 204);
    }

    public function likedCollections(): Response
    {
        $liked_collections = $this->collectionRepository->likedCollections();

        return response([
            'liked_collections' => $liked_collections
        ]);
    }

    public function toggleCollectionLike(Collection $collection): Response
    {
        $this->collectionRepository->toggleCollectionLike($collection);

        return response([
            'message' => 'like status toggled.'
        ]);
    }
}
