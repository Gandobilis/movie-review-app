<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(RateRequest $request)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RateRequest $request, Rate $rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        $rate->delete();

        return response(status: 204);
    }
}
