<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    public function courtCategories()
    {
        return $this->hasMany(Court_Cat::class, 'state', 'code');
    }

    public function vCourtCategories()
    {
        return $this->hasMany(VCourt_cat::class, 'state', 'code');
    }
}
