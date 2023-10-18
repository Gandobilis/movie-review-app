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
        Collection::factory()
            ->count(99)
            ->create()
            ->each(function ($collection) {
                $users = User::inRandomOrder()->limit(rand(1, 99))->get();
                $movies = Movie::inRandomOrder()->limit(rand(1, 500))->get();

                $collection->likes()->attach($users);
                $collection->movies()->attach($movies);
            });
    }
}
