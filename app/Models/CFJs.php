<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CFJs extends Model
{
    protected $table = 'c_f_js';

    public $incrementing = false;

    protected $fillable = [
        'j_code',
        'j_name',
        'cf_code',
        'j_desc',
        'j_order',
        'j_serperator',
        'j_oppsoit',
        'user_id',
    ];

    public function cFile()
    {
        return $this->belongsTo(CFile::class, 'cf_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
