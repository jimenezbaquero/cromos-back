<?php

namespace App\Headers;

class PublisherHeader
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
            'collections' => [
                'label' => 'collections',
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