<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getUsers(): Collection
    {
        return User::paginate(config('paginate.default'));
    }

    public function storeUser($data): User
    {
        return User::create($data);
    }

    public function showUser(User $user): void
    {

    }

    public function updateUser(User $user, $data): void
    {
        $user->update($data);
    }

    public function destroyUser(User $user): void
    {
        $user->delete();
    }
}
