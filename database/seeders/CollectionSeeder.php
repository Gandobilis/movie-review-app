<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Collection::factory()
            ->count(65)
            ->create()
            ->each(function ($collection) {
                $movies = Movie::inRandomOrder()->limit(rand(1, 10))->get();
                $collection->movies()->attach($movies);
            });
    }
}
