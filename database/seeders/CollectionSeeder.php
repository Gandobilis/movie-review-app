<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all()->pluck('id');
        $movies = Movie::all()->pluck('id');
        $collections = Collection::factory()->count(99)->create();

        $collections->each(function ($collection) use ($users, $movies) {
            $_users = $users->random(1, 99);
            $_movies = $movies->random(1, 500);

            $collection->likes()->attach($_users);
            $collection->movies()->attach($_movies);
        });
    }
}
