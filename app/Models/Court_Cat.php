<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court_Cat extends Model
{
    protected $table = 'court__cats';

    protected $fillable = [
        'code',
        'state',
        'description',
        'appeal',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'code');
    }

    public function courts()
    {
        return $this->hasMany(Court::class, 'cat', 'code');
    }
}
