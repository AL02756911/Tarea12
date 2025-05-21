<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'description'
    ];

    public function media()
    {
        return $this->belongsToMany(Media::class, 'character_media');
    }
}
