<?php

namespace App\Http\Controllers;

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

        return response(['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): Response
    {
        $data = $request->validated();
        $image = $this->fileUploadService->uploadFile($data['image'], 'users');
        unset($data['image']);

        $user = User::create($data);
        $user->image()->create(['image' => $image]);
        $user->load('image');

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
        $user->load('image');

        return response(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): Response
    {
        $data = $request->validated();
        if (isset($data['image'])) {
            $this->fileUploadService->deleteFile($user->image?->image);

            $image = $this->fileUploadService->uploadFile($data['image'], 'users');
            $user->image()->update(['image' => $image]);
        }

        unset($data['image']);
        $user->update($data);
        $user->load('image');

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
        $this->fileUploadService->deleteFile($user->image?->image);
        $user->image?->delete();
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
        $user->load('image');

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
        $user->load('image');

        return response([
            'message' => __('user.success.deactivate'),
            'user' => $user
        ]);
    }
}
