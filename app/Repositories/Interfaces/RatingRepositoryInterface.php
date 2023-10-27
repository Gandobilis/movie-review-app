<?php

namespace App\Repositories\Interfaces;

use App\Models\Rating;

interface RatingRepositoryInterface
{
    public function storeRating(array $data);

    public function updateRating(Rating $rating, array $data);

    public function destroyRating(Rating $rating);
}
