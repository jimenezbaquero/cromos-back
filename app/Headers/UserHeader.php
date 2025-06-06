<?php

namespace App\Headers;

class UserHeader
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
            'email' => [
                'label' => 'email',
                'type' => 'text',
                'filterable' => true,
                'sortable' => true,
            ],
            'role' => [
                'label' => 'role',
                'type' => 'text',
                'filterable' => false,
                'sortable' => true,
                'funnel' => true
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