<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Collection;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::paginate(config('paginate.default'));

        return response([
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): Response
    {
        $data = $request->validated();

        $user = User::create($data);

        return response([
            'message' => __('user.success.store'),
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        return response([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): Response
    {
        $data = $request->validated();

        $user->update($data);

        return response([
            'message' => __('user.success.update'),
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): Response
    {
        $user->delete();

        return response([
            'message' => __('user.success.destroy'),
        ]);
    }

    public function liked_collections(): Response
    {
        $liked_collections = auth()->user()->liked_collections;

        return response([
            'liked_collections' => $liked_collections
        ]);
    }

    public function toggle_collection_like(string $collection_id): Response
    {
        try {
            Collection::findOrFail($collection_id);
        } catch (ModelNotFoundException $e) {
            return response(status: 404);
        }

        auth()->user()->liked_collections()->toggle($collection_id);
        return response()->noContent();
    }

    public function toggle_movie_view(string $movie_id): Response
    {
        try {
            Movie::findOrFail($movie_id);
        } catch (ModelNotFoundException $e) {
            return response(status: 404);
        }

        auth()->user()->viewed_movies()->toggle($movie_id);
        return response()->noContent();
    }
}
