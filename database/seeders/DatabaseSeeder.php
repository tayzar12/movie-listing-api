<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->count(1)->create();
        \App\Models\Genre::factory()->count(10)->create();
        \App\Models\Tag::factory()->count(20)->create();
        \App\Models\Author::factory()->count(30)->create();
        \App\Models\Movie::factory()->count(50)->create();
        \App\Models\Comment::factory()->count(100)->create();


        $genres = \App\Models\Genre::all();
        $tags = \App\Models\Tag::all();

        \App\Models\Movie::all()->each(function ($movie) use ($genres, $tags) {
            $movie->genres()->attach(
                $genres->random(rand(1, 3))->pluck('id')->toArray()
            );

            $movie->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
