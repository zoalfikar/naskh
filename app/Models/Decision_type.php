<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decision_type extends Model
{
    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'description',
    ];


}
