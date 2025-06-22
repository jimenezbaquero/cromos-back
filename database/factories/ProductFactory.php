<?php

namespace Database\Factories;

use App\Helper\FactoryHelper;
use App\Models\Card;
use App\Models\CardType;
use App\Models\Collection;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publisher>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'price' => rand(10,1000),
            'quantity' => rand(10,100),
        ];
    }
    


}
