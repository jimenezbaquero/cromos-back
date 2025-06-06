<?php

namespace App\Transformers;

use App\Models\Collection;
use Illuminate\Support\Str;

class CollectionTransformer
{
    public static function transformToWebIndex(Collection $collection)
    {
        return [
            'id' => $collection->id,
            'name' => $collection->name,
            'description' => Str::limit($collection->description,50),
            'year' => $collection->year,
            'publisher' => $collection->publisher ? $collection->publisher->name : '---',
            'cards' => $collection->cards()->count(),
            'created_at' => $collection->created_at->format('d/m/Y'),
        ];
    }
}

