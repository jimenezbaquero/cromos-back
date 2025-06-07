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
        } else {
            foreach ($filters as $key => $filter) {
                if(in_array($key ,['search','page'])){
                    continue;
                }
                if ($filter['value'] != '') {
                    if (str_contains($filter['field'],'count')){
                        $relation = explode('_', $filter['field'])[0];
                        $query->withCount($relation)->having($relation.'_count',$filter['value']);
                    } else {
                        $query->where($filter['field'], 'like', '%' . $filter['value'] . '%');
                    }
                } else if (!empty($filter['funnel'])) {
                    $cont = 0;
                    if (!str_contains($filter['field'], 'relation')) {
                        $query->where(function ($q) use ($cont, $filter) {
                            foreach ($filter['funnel'] as $option) {
                                if (isset($option['value']) && $option['value']) {
                                    $action = $cont == 0 ? 'where' : 'orWhere';
                                    $q->$action($filter['field'], $option['id']);
                                    $cont++;
                                }
                            }
                        });
                    } else {
                        $relation = explode('_', $filter['field'])[0];
                        $query->whereHas($relation, function ($q) use ($filter, $cont) {
                            foreach ($filter['funnel'] as $option) {
                                if (isset($option['value']) && $option['value']) {
                                    $action = $cont == 0 ? 'where' : 'orWhere';
                                    $q->$action('id', $option['id']);
                                    $cont++;
                                }
                            }
                        });
                    }
                }
                if ($filter['sort'] != '') {
                    if (str_contains($filter['field'], 'count')) {
                        $relation = explode('_', $filter['field'])[0];
                        $query->withCount($relation);
                    }
                    $query->orderBy($filter['field'], $filter['sort']);
                }
            }
        }

        return $query;
    }
}
