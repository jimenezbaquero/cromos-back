<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardType extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'height',
        'width'
    ];
    
    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Card::class);
    }
}
