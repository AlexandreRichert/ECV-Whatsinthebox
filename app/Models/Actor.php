<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $fillable = ['name', 'photo', 'tmdb_id'];
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'actor_movie');
    }

    public function shows()
    {
        return $this->belongsToMany(Show::class, 'actor_show');
    }
}
