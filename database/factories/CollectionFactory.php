<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publisher>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $publisher = Publisher::inRandomOrder()->first();

        return [
            'name' => fake()->words(3,true),
            'description' => fake()->text(),
            'year' => rand(1975, 2025),
            'publisher_id' => $publisher->id,
        ];
    }
}
