<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VCourt extends Model
{
protected   $fillable = [
        'code',
        'name',
        'cat',
        'user_id',
        'correction'
    ];

    public function catigory()
    {
        return $this->belongsTo(VCourt_cat::class, 'cat', 'code');
    }
}
