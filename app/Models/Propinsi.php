<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propinsi extends Model
{
    use HasFactory;
    protected $primaryKey = 'kd_provinsi'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_provinsi',
        'provinsi',
    ];

    // Definisikan relasi ke tabel mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'kd_provinsi', 'kd_provinsi');
    }
}
