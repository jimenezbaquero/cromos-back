<?php

namespace App\Transformers;

use App\Models\Publisher;

class PublisherTransformer
{
    public static function transformToWebIndex(Publisher $publisher)
    {
        return [
            'id' => $publisher->id,
            'name' => $publisher->name,
            'collections' => $publisher->collections()->count(),
            'created_at' => $publisher->created_at->format('d/m/Y'),
        ];
    }
}