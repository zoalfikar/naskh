<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dec_tab extends Model
{
    protected $table = 'dec_tabs';

    public $incrementing = false;

    protected $fillable = [
        'tab_code',
        'tab_desc',
        'tab_order',
        'tab_value',
        'tab_font_s',
        'cf_code',
        'descision_n',
        'descision_d',
        'not_printed',
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
