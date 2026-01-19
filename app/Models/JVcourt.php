<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JVcourt extends Model
{
    protected $table = 'j_vcourts';

    // public $incrementing = false;

    protected $fillable = [
        'j_code',
        'vcourt',
        'active',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'j_code', 'code');
    }

    public function vCourt()
    {
        return $this->belongsTo(VCourt::class, 'vcourt', 'code');
    }
}
