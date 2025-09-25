<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'tmdb_id',
        'seen',
        'season_number',
        'episode_number',
        'vote_average',
        'season_id',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
