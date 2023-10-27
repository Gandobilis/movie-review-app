<?php

namespace App\Repositories;

use App\Models\Rating;
use App\Repositories\Interfaces\RatingRepositoryInterface;

class RatingRepository implements RatingRepositoryInterface
{

    public function storeRating(array $data): Rating
    {
        $data['user_id'] = auth()->id();

        return Rating::create($data);
    }

    public function updateRating(Rating $rating, array $data): void
    {
        $rating->update($data);
    }

    public function destroyRating(Rating $rating): void
    {
        $rating->delete();
    }
}
