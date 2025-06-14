<?php

namespace App\Transformers;

use App\Models\Card;

class CardTransformer
{
    public static function transformToWebIndex(Card $card)
    {
        return [
            'id' => $card->id,
            'number' => $card->number,
            'collection' => $card->collection->name,
            'card_type' => $card->card_type->name,
            'url' => $card->url,
            'publisher' => $card->publisher->name ,
            'probability' => $card->probability,
            'created_at' => $card->created_at->format('d/m/Y'),
        ];
    }
}

