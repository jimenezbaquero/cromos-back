<?php

namespace App\Filters;

use App\Helper\OptionHelper;

class CollectionFilter
{
    public static function getFilters() {
        return [
            'id' => [
                'field' => 'collections.id',
                'value' => '',
                'sort' => '',
            ],
            'name' => [
                'field' => 'collections.name',
                'value' => '',
                'sort' => '',
            ],
            'description' => [
                'field' => 'collections.description',
                'value' => '',
                'sort' => '',
            ],
            'publisher' => [
                'field' => 'collections.publisher_id',
                'value' => '',
                'sort' => '',
                'funnel' => []
            ],
            'cards' => [
                'field' => 'cards_count',
                'value' => '',
                'sort' => '',
            ],
            'created_at' => [
                'field' => 'collections.created_at',
                'value' => '',
                'sort' => '',
            ],
            'search' => [
                'field' => 'collections.name|collections.description',
                'value' => '',
                'sort' => '',
            ],
            'page' => [
                'value' => 1
            ]
        ];
    }

    public static function getFunnelOptions() {
        return [
            "publisher" => OptionHelper::getPublisherOptions(),
        ];
    }
}
