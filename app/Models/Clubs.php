<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'name',
        'logo',
    ];

    // relacion inversa
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    // hidden
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
