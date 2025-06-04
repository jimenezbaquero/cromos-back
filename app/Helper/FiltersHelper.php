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
                }else if(!empty($filter['funnel'])){
                    $cont = 0;
                    if(!in_array($key,['role'])) {
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
                        switch ($key){
                            case('role'):{
                                $query->whereHas('roles', function($q) use ($filter,$cont) {
                                    foreach ($filter['funnel'] as $option) {
                                        if (isset($option['value']) && $option['value']) {
                                            $action = $cont == 0 ? 'where' : 'orWhere';
                                            $q->$action($filter['field'], $option['id']);
                                            $cont++;
                                        }
                                    }
                                });
                            }
                        }
                    }
                }
                if ($filter['sort'] != '') {
                    $query->orderBy($filter['field'], $filter['sort']);
                }
            }
        }

        return $query;
    }
}
