<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    public function __construct(private readonly ProfileRepositoryInterface $profileRepository)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(): Response
    {
        return response([
            'user' => $this->profileRepository->showProfile()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request): Response
    {
        $this->profileRepository->updateProfile($request->validated());

        return $this->show();
    }
}
