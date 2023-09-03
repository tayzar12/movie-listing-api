<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email,
            'text' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'commentable_id' => Movie::all()->random(1)->first()->id,
            'commentable_type' => Movie::class,
        ];
    }
}
