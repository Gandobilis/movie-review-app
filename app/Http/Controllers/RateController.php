<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateRequest;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RateController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(RateRequest $request): Response
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $rate = Rate::create($data);

        return response([
            'message' => 'Rating added successfully',
            'rate' => $rate
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RateRequest $request, Rate $rate): Response
    {
        $data = $request->validated();

        $rate->update($data);

        return response([
            'message' => 'Rating updated successfully',
            'rate' => $rate
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate): Response
    {
        $rate->delete();

        return response(status: 204);
    }
}
