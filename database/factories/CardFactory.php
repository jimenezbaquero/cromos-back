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
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $collection = Collection::inRandomOrder()->first();
        $cardType = CardType::inRandomOrder()->first();

        return [
            'number' => FactoryHelper::uniqueNumberInCollection(),
            'collection_id' => $collection->id,
            'card_type_id' => $cardType->id,
            'url_photo'=> $cardType->name == 'horizontal'? Storage::path('card_photos/cromo_horizontal.png') : Storage::path('card_photos/cromo_vertical.png'),
        ];
    }
    


}
