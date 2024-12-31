<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSekolah extends Model
{
    use HasFactory;
    
     // Nama tabel dalam basis data
     protected $table = 'jenis_sekolah_mahasiswa_baru';

     // Primary key tabel
     protected $primaryKey = 'kd_jenis_sekolah';
 
     // Kolom yang dapat diisi (mass assignable)
     protected $fillable = [
         'kd_jenis_sekolah',
         'jenis_sekolah',
     ];
 
     // Definisikan relasi ke tabel mahasiswa (jika ada)
     public function mahasiswa()
     {
         return $this->belongsTo(Mahasiswa::class, 'kd_jenis_sekolah', 'kd_jenis_sekolah');
     }
}
