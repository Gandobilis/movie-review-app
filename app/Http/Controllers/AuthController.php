<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    private function res($message, $data = [], $statusCode = ResponseAlias::HTTP_OK): Response
    {
        return response([
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function login(LoginRequest $request): Response
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) {
            return $this->res(__('auth.unauthorized'), [], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $user = auth()->user();

        if (!$user->active) {
            auth()->logout();
            return $this->res(__('auth.forbidden'), [], ResponseAlias::HTTP_FORBIDDEN);
        }

        $token = $user->createToken('MovieReviewAppApiToken')->plainTextToken;

        return $this->res(__('auth.log_in'), ['user' => $user, 'access_token' => $token]);
    }

    public function logout(): Response
    {
        auth()->user()->tokens()->delete();

        return $this->res(__('auth.log_out'));
    }
}
