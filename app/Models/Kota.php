<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;
    protected $primaryKey = 'kd_kota'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_provinsi',
        'kd_kota',
        'kota'
    ];

    // Definisikan relasi ke tabel provinsi
    public function provinsi()
    {
        return $this->belongsTo(Propinsi::class, 'kd_provinsi', 'kd_provinsi');
    }

    // Definisikan relasi ke tabel mahasiswa
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kd_kota', 'kd_kota');
    }
}
