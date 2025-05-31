<?php

namespace App\Helper;

use App\Models\Card;
use App\Models\Collection;

class FactoryHelper
{
    static public function uniqueNumberInCollection(): array {
        $collection = Collection::inRandomOrder()->first();
        $usedNumbers = Card::where('collection_id', $collection->id)->pluck('number')->toArray();
        
        do {
            $number = rand(1, 400);
        } while (in_array($number, $usedNumbers));
        
        return [
            'collection_id' => $collection->id,
            'number' => $number,
        ];
        
    }
}