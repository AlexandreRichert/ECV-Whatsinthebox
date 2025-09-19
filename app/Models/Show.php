<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    public function seasons()
    {
        return $this->hasMany(Season::class);
    }
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_show');
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'actor_show');
    }
}
