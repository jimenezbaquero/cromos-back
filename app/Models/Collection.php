<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'year',
        'publisher_id'
    ];
    
    
    public function publisher(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Publisher::class);
    }
}
