<?php

namespace App\Helper;

use App\Models\Collection;
use App\Models\Publisher;
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
        $roles = Role::where('name','<>','guest')->get();
        $pairs = [];
        foreach ($roles as $role) {
            $pairs[] = (object)['name' => $role['name'], 'id' => $role['id']];
        }
        return self::createOptions($pairs);
    }

    public static function getPublisherOptions() {
        $publishers = Publisher::all();
        $pairs = [];
        foreach ($publishers as $publisher) {
            $pairs[] = (object)['name' => $publisher['name'], 'id' => $publisher['id']];
        }
        return self::createOptions($pairs);
    }
}
