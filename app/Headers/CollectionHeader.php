<?php

namespace App\Headers;

class CollectionHeader
{
    public static function getHeaders(){
        return [
            'id' => [
                'label' => 'id',
                'type' => 'text',
                'filterable' => false,
                'sortable' => true,
            ],
            'name' => [
                'label' => 'name',
                'type' => 'text',
                'filterable' => true,
                'sortable' => true,
            ],
            'description' => [
                'label' => 'description',
                'type' => 'text',
                'filterable' => true,
                'sortable' => false,
            ],
            'publisher' => [
                'label' => 'publisher',
                'type' => 'text',
                'filterable' => false,
                'sortable' => true,
                'funnel' => true
            ],
            'cards' => [
                'label' => 'cards',
                'type' => 'number',
                'filterable' => true,
                'sortable' => true,
            ],
            'created_at' => [
                'label' => 'created_at',
                'type' => 'date',
                'filterable' => true,
                'sortable' => true,
            ],
        ];
    }
}
