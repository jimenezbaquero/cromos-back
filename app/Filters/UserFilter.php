<?php

namespace App\Filters;

use App\Helper\OptionHelper;

class UserFilter
{
    public static function getFilters() {
        return [
            'id' => [
                'field' => 'users.id',
                'value' => '',
                'sort' => '',
            ],
            'name' => [
                'field' => 'users.name',
                'value' => '',
                'sort' => '',
            ],
            'email' => [
                'field' => 'users.email',
                'value' => '',
                'sort' => '',
            ],
            'role' => [
                'field' => 'relation_roles',
                'value' => '',
                'sort' => '',
                'funnel' => []
            ],
            'created_at' => [
                'field' => 'users.created_at',
                'value' => '',
                'sort' => '',
            ],
            'search' => [
                'field' => 'users.name|users.email',
                'value' => '',
                'sort' => '',
            ],
            'page' => [
                'page' => 1,
                'perPage' => 10
            ]
        ];
    }

    public static function getFunnelOptions() {
        return [
            "role" => OptionHelper::getRoleOptions(),
        ];
    }
}
