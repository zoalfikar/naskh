<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VCourt_cat extends Model
{
    protected $table = 'v_court_cats';

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

    public function vCourts()
    {
        return $this->hasMany(VCourt::class, 'cat', 'code');
    }
}
