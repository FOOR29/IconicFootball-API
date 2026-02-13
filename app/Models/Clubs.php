<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clubs extends Model
{
    protected $fillable = [
        'name',
        'logo',
    ];

    public function players()
    {
        return $this->hasMany(Players::class);
    }

    // hidden
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
