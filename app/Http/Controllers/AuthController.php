<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request): Response
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) {
            return response([
                'message' => __('auth.unauthorized')
            ], 401);
        }

        if (!auth()->user()->active) {
            auth()->logout();

            return response([
                'message' => __('auth.forbidden')
            ], 403);
        }

        $access_token = auth()->user()->createToken('MovieReviewAppApiToken')->plainTextToken;


        return response([
            'user' => auth()->user(),
            'access_token' => $access_token
        ]);
    }

    public function logout(): Response
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => __('auth.log_out')
        ]);
    }
}
