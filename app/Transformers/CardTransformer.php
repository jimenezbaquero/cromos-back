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
            'card_type' => $card->cardType->name,
            'url' => $card->url_photo,
            'probability' => $card->probability,
            'created_at' => $card->created_at->format('d/m/Y'),
        ];
    }
}

