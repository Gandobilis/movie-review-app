<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Lasha Gagnidze',
            'email' => 'lashadeveloper@gmail.com',
            'role' => 'admin'
        ]);
        User::factory()->count(12)->create();

    }
}
