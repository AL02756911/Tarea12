<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'title',
        'genre',
        'release_date',
        'review',
        'season'
    ];

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_media');
    }
}
