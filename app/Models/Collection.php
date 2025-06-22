<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'total_cards',
        'year',
        'publisher_id'
    ];
    
    
    public function publisher(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Publisher::class);
    }
    
    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Card::class);
    }
    
    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }
}
