<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{

    public function showProfile()
    {
        return auth()->user()->load('genres');
    }

    public function updateProfile(array $data): void
    {
        $user = auth()->user();
        $user->update($data);
        $user->genres()->sync($data['genre_ids']);
    }
}
