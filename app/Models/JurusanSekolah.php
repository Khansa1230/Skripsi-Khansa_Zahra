<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurusanSekolah extends Model
{
    use HasFactory;
     // Nama tabel dalam basis data
     protected $table = 'jurusan_sekolah_mahasiswa_baru';

     // Primary key tabel
     protected $primaryKey = 'kd_jurusan_sekolah';
 
     // Kolom yang dapat diisi (mass assignable)
     protected $fillable = [
         'kd_jenis_sekolah',
         'kd_jurusan_sekolah',
         'jurusan_sekolah_mahasiswa_baru',
     ];
     public function jenissekolah()
    {
        return $this->belongsTo(JenisSekolah::class, 'kd_fakultas', 'kd_fakultas');
    }

 
     // Definisikan relasi ke tabel mahasiswa (jika ada)
     public function mahasiswa()
     {
         return $this->belongsTo(Mahasiswa::class, 'kd_jurusan_sekolah', 'kd_jurusan_sekolah');
     }

     
}
