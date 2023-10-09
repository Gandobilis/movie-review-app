<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private readonly FileUploadService $fileUploadService)
    {
    }

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
    public function store(UserCreateRequest $request): Response
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'users');
        }

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
    public function update(UserUpdateRequest $request, User $user): Response
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            if (isset($user->image)) {
                $this->fileUploadService->deleteFile($user->image);
            }

            $data['image'] = $this->fileUploadService->uploadFile($data['image'], 'users');
        }

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
        if (isset($user->image)) {
            $this->fileUploadService->deleteFile($user->image);
        }

        $user->delete();

        return response([
            'message' => __('user.success.destroy'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function activate(User $user): Response
    {
        $user->update(['active' => true]);

        return response([
            'message' => __('user.success.activate'),
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function deactivate(User $user): Response
    {
        $user->update(['active' => false]);

        return response([
            'message' => __('user.success.deactivate'),
            'user' => $user
        ]);
    }
}
