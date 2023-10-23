<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getUsers();

    public function storeUser($data);

    public function showUser(User $user);

    public function updateUser(User $user, $data);

    public function destroyUser(User $user);
}
