<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    protected $fillable = ['id_genre_tmdb', 'name'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    public function shows(): BelongsToMany
    {
        return $this->belongsToMany(Show::class, 'genre_show');
    }
}
