<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'cf_code',
        'number',
        'date',
        'note',
        'user_id',
        'croup',
        'hurry_date',
        'kind',
        'hurry',
        'type',
        'hurry_text',
        'opposit_judge',
        'higry_date'
    ];

    public function cFile()
    {
        return $this->belongsTo(CFile::class, 'cf_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function decisionType()
    {
        return $this->belongsTo(Decision_type::class, 'type', 'code');
    }
}
