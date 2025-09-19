<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $fillable = ['name', 'photo', 'tmdb_id'];
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
