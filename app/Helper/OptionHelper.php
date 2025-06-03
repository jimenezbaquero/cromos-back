<?php

namespace App\Helper;

use App\Models\Collection;
use Spatie\Permission\Models\Role;

class OptionHelper
{
   public static function makeOptions($collection){
       return $collection->map(function($item){
           return ['label'=> $item->name, 'id' => $item->id];
       });
   }
   
   public static function getRoleOptions(){
       $roles = Role::all();
       return self::makeOptions($roles);
   }
}