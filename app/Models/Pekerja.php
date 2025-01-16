<?php
// File: app/Models/Pekerja.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pekerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'ni',
        'nama',
        'role',
        'kd_jurusan',
        'jurusan',
        'email',
    ];

    protected $table = 'pekerja';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'ni', 'nim');
    }
}
