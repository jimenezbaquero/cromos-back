<?php

namespace App\Filters;

class PublisherFilter
{
    public static function getFilters() {
        return [
            'id' => [
                'field' => 'publishers.id',
                'value' => '',
                'sort' => '',
            ],
            'name' => [
                'field' => 'publishers.name',
                'value' => '',
                'sort' => '',
            ],
            'collections' => [
                'field' => 'collections_count',
                'value' => '',
                'sort' => '',
            ],
            'created_at' => [
                'field' => 'publishers.created_at',
                'value' => '',
                'sort' => '',
            ],
            'search' => [
                'field' => 'publishers.name',
                'value' => '',
                'sort' => '',
            ],
            'page' => [
                'value' => 1
            ]
        ];
    }
}
