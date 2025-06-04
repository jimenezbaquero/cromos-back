<?php

namespace App\Helper;

use App\Models\Collection;
use Spatie\Permission\Models\Role;

class OptionHelper {
    public static function createOptions($collection) {
        return array_map(function ($item) {
            return [
                'label' => $item->name,
                'id' => $item->id,
                'value' => false
            ];
        }, $collection);
    }

    public static function getRoleOptions() {
        $roles = Role::all();
        $pairs = [];
        foreach ($roles as $role) {
            $pairs[] = (object)['name' => $role['name'], 'id' => $role['id']];
        }
        return self::createOptions($pairs);
    }
}
