<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabs extends Model
{
    protected $table = 'tabs';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'description',
        'order',
        'courts',
        'not_printed',
        'group'
    ];

    protected $casts = [
        'courts' => 'array',
    ];
}
