<?php

namespace App\Helper;

class TransformHelper {
    public static function transform($class, $items){
        $transforms = $items->getCollection()->map(function ($item) use ($class) {
            return $class::transformToWebIndex($item);
        });
        $items->setCollection($transforms);

        return $items;
    }
}
