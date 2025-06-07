<?php

namespace App\Filters;

use App\Helper\OptionHelper;

class CardFilter
{
    public static function getFilters() {
        return [
            'number' => [
                'field' => 'cards.number',
                'value' => '',
                'sort' => '',
            ],
            'description' => [
                'field' => 'collections.description',
                'value' => '',
                'sort' => '',
            ],
            'card_type' => [
                'field' => 'cards.card_type_id',
                'value' => '',
                'sort' => '',
                'funnel' => []
            ],
            'collection' => [
                'field' => 'cards.collection_id',
                'value' => '',
                'sort' => '',
                'funnel' => []
            ],
            'probability' => [
                'field' => 'cards.probability',
                'value' => '',
                'sort' => '',
            ],
            'created_at' => [
                'field' => 'collections.created_at',
                'value' => '',
                'sort' => '',
            ],
            'search' => [
                'field' => 'cards.number|cards.probability',
                'value' => '',
                'sort' => '',
            ],
            'page' => [
                'page' => 1,
                'perPage' => 5
            ]
        ];
    }

    public static function getFunnelOptions() {
        return [
            "card_type" => OptionHelper::getCardTypeOptions(),
            "collection" => OptionHelper::getCollectionOptions()
        ];
    }
}
