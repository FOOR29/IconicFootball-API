<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
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
        return $this->belongsTo(Club::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // ocultar atributos
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
