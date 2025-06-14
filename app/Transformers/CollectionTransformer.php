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
    
    public static function transformToWebShow(Collection $collection){
        return [
            'id' => $collection->id,
            'name' => $collection->name,
            'description' => $collection->description,
            'year' => $collection->year,
            'cover_url' =>$collection->cover_url??'',
            'backcover_url' =>$collection->backcover_url??'',
            'publisher' => $collection->publisher ? $collection->publisher->name : '---',
            'card_number' => $collection->cards()->count(),
            'created_at' => $collection->created_at->format('d/m/Y'),
        ];
    }
}

