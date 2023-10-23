<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use Illuminate\Http\Response;

class RatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingRequest $request): Response
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $rating = Rating::create($data);

        return response([
            'message' => 'Rating added successfully',
            'rating' => $rating
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RatingRequest $request, Rating $rating): Response
    {
        $data = $request->validated();

        $rating->update($data);

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
        $rating->delete();

        return response(status: 204);
    }
}
