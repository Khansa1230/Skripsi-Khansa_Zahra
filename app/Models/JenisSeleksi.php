<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSeleksi extends Model
{
    use HasFactory;
    protected $primaryKey = 'kd_jenisseleksi'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_jenis_seleksi',
        'jenis_seleksi',
    ];
    public function mahasiswa()
    {
        return $this->hbelongsTo(Mahasiswa::class, 'kd_jenis_seleksi', 'kd_jenis_seleksi');
    }
}
