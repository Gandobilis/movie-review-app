<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $movie = Movie::inRandomOrder()->first();

        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'comment' => $this->faker->sentence,
        ];
    }
}
