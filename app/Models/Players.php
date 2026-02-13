<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'known_as',
        'full_name',
        'img',
        'prime_season',
        'prime_position',
        'preferred_foot',
        'spd',
        'sho',
        'pas',
        'dri',
        'def',
        'phy',
        'prime_rating',
        'club_id',
        'country_id',
    ];

    // relaciones
    public function club()
    {
        return $this->belongsTo(Clubs::class);
    }

    public function country()
    {
        return $this->belongsTo(Countries::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
