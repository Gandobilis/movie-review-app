<?php

namespace App\Repositories\Interfaces;

interface ProfileRepositoryInterface
{
    public function showProfile();

    public function updateProfile(array $data);
}
