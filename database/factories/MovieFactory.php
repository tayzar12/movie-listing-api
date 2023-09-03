<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($this->faker));

        return [
            'title' => $this->faker->movie,
            'summary' => $this->faker->overview,
            'author_id' => Author::all()->random(1)->first()->id,
            'imdb_rating' => rand(3, 10),
        ];

    }
}
