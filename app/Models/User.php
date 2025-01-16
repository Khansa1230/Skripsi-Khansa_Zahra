<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_uin',
        'nim',
        'nama',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nim_verified_at' => 'datetime',
        'email_uin_verified_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel matakuliah.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function matakuliah()
    {
        return $this->hasOne(Matakuliah::class, 'nim', 'nim');
    }

    public function pekerja()
    {
        return $this->hasOne(Pekerja::class, 'ni', 'nim');
    }
}
