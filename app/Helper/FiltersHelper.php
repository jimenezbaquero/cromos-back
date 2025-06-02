<?php

namespace App\Helper;


use Illuminate\Database\Eloquent\Builder;

class FiltersHelper
{
    public static function applyTableFilter(Builder $query, $filters) {
        if (isset($filters['search']) && $filters['search']['value'] != '') {
            $filter = $filters['search'];
            $query->where(function ($q) use ($filter) {
                $fields = explode('|', $filter['field']);
                $field = array_shift($fields);
                $q->where($field, 'like', '%' . $filter['value'] . '%');
                foreach ($fields as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $filter['value'] . '%');
                }
            });
        }
        if (isset($filters['filters'])) {
            foreach ($filters['filters'] as $key => $filter) {
                if ($filter['value'] != '') {
                    $query->where($filter['field'], 'like', '%' . $filter['value'] . '%');
                }
                if ($filter['sort'] != '') {
                    $query->orderBy($filter['field'], $filter['sort']);
                }
            }
        }
        
        return $query;
    }
}