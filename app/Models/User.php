<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'password',
        'role',
        'state',
        'vcourt',
        'vcourt_name',  
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'code');
    }

    public function vCourt()
    {
        return $this->belongsTo(VCourt::class, 'vcourt', 'code');
    }

    public function cFiles()
    {
        return $this->hasMany(CFile::class, 'user_id');
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class, 'user_id');
    }

    public function decTabs()
    {
        return $this->hasMany(Dec_tab::class, 'user_id');
    }

    public function cFJs()
    {
        return $this->hasMany(CFJs::class, 'user_id');
    }
}
