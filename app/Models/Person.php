<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'national_no',
        'name',
        'role',
        'notes',
    ];
}
