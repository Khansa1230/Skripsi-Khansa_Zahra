<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    protected $primaryKey = 'kd_jurusan'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_fakultas',
        'kd_jurusan',
        'jurusan'
    ];

    // Definisikan relasi ke tabel Fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'kd_fakultas', 'kd_fakultas');
    }
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'kd_jurusan', 'kd_jurusan');
    }
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kd_jurusan', 'kd_jurusan');
    }
}
