<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CFile extends Model
{

    protected $fillable = [
        'code',
        'v_corte',
        'number',
        'subject',
        'kind',
        'c_date',
        'c_begin_n',
        'c_start_year',
        'round_year',
        'degree1_court',
        'degree1_state',
        'degree1_room',
        'degree1_number',
        'degree1_year',
        'degree1_dec_n',
        'degree1_dec_d',
        'degree2_court',
        'degree2_state',
        'degree2_room',
        'degree2_number',
        'degree2_year',
        'degree2_dec_n',
        'degree2_dec_d',
        'user_id'
    ];

    
    public function vCourt()
    {
        return $this->belongsTo(VCourt::class, 'v_corte', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jVcourts()
    {
        return $this->hasMany(JVcourt::class, 'j_code', 'code');
    }

    public function decisions()
    {
        return $this->hasOne(Decision::class, 'cf_code', 'code');
    }
}
