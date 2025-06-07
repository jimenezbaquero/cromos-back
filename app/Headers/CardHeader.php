<?php

namespace App\Headers;

class CardHeader
{
    public static function getHeaders(){
        return [
            'preview' => [
                'label' => 'preview',
                'type' => 'image',
                'filterable' => false,
                'sortable' => false
            ],
            'number' => [
                'label' => 'number',
                'type' => 'text',
                'filterable' => true,
                'sortable' => true,
            ],
            'card_type' => [
                'label' => 'type',
                'type' => 'text',
                'filterable' => false,
                'sortable' => true,
                'funnel' => true
            ],
            'probability' => [
                'label' => 'probability',
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
