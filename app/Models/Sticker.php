<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'card_id'
    ];
    
    public function package(){
        return $this->belongsTo(Package::class);
    }
    
    public function card(){
        return $this->belongsTo(Card::class);
    }
}
