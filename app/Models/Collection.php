<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    
    /**
     * @OA\Schema(
     *     schema="Coleccion",
     *     type="object",
     *     title="Colección",
     *     required={"id", "name"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Colección de Verano")
     * )
     */
    
    protected $fillable = [
        'name',
        'description',
        'year',
        'publisher_id'
    ];
    
    
    public function publisher(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Publisher::class);
    }
    
    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Card::class);
    }
}
