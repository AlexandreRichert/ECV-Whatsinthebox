<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = ['name', 'show_id', 'season_number'];
    public function show()
    {
        return $this->belongsTo(Show::class);
    }
    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
