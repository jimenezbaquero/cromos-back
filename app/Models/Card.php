<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'collection_id',
        'card_type_id',
        'url_photo'
    ];
    
    public function cardType(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(CardType::class);
    }
    
    public function collection(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Collection::class);
    }
}
