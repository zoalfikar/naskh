<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = [
        'code',
        'name',
        'cat',
        'active',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Court_Cat::class, 'cat', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
