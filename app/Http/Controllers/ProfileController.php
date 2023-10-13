<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(): Response
    {
        $user = auth()->user();
        $user->load('genres');

        return response([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request): Response
    {
        $data = $request->validated();
        $genre_ids = $data['genre_ids'];
        unset($data['genre_ids']);

        $user = auth()->user();
        $user->update($data);
        $user->genres()->sync($genre_ids);

        $user->load('genres');

        return response([
            'user' => $user
        ]);
    }
}
