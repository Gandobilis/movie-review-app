<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\TestMail;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = $this->userRepository->getUsers();

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

        $user = $this->userRepository->storeUser($data);

        Mail::to($user->email)->send(new TestMail($user));

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

        $this->userRepository->updateUser($user, $data);

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
        $this->userRepository->destroyUser($user);

        return response([
            'message' => __('user.success.destroy'),
        ]);
    }
}
