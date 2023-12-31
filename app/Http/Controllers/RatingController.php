<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use Illuminate\Http\Response;

class RatingController extends Controller
{
    public function __construct(private readonly RatingRepositoryInterface $ratingRepository)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingRequest $request): Response
    {
        return response([
            'message' => 'Rating added successfully',
            'rating' => $this->ratingRepository->storeRating($request->validated())
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RatingRequest $request, Rating $rating): Response
    {
        $this->ratingRepository->updateRating($rating, $request->validated());

        return response([
            'message' => 'Rating updated successfully',
            'rating' => $rating
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating): Response
    {
        $this->ratingRepository->destroyRating($rating);

        return response(status: 204);
    }
}
