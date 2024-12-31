<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

     // Nama tabel dalam basis data
     protected $table = 'mahasiswa';
    
     // Primary key tabel
     protected $primaryKey = 'nim';
     
   
     
     // Kolom yang dapat diisi (mass assignable)
     protected $fillable = [
         'nim',
         'nama',
         'tanggal_lahir',
         'tempat_lahir',
         'kelamin',
         'tahun_angkatan',
         'kd_jenis_seleksi',
         'jenis_seleksi',
         'wni_wna',
         'kd_propinsi',
         'propinsi',
         'kd_kota',
         'kota',
         'status',
         'kd_fakultas',
         'fakultas',
         'kd_jurusan',
         'jurusan',
         'jenjang',
         'tanggal_lulus',
         'semester_lulus',
         'ipk',
         'sks',
         'keterangan',
         'kd_jenis_sekolah',
         'jenis_sekolah_mahasiswa_baru',
         'kd_jurusan_sekolah',
         'jurusan_sekolah_mahasiswa_baru'
     ];
 
     // Relasi ke model Fakultas
     public function fakultas()
     {
         return $this->belongsTo(Fakultas::class, 'kd_fakultas', 'kd_fakultas');
     }
 
     // Relasi ke model Jurusan
     public function jurusan()
     {
         return $this->belongsTo(Jurusan::class, 'kd_jurusan', 'kd_jurusan');
     }
 
     // Relasi ke model JenisSeleksi
     public function jenisseleksi()
     {
         return $this->belongsTo(JenisSeleksi::class, 'kd_jenis_seleksi', 'kd_jenis_seleksi');
     }
 
     // Relasi ke model Propinsi
     public function propinsi()
     {
         return $this->belongsTo(Propinsi::class, 'kd_propinsi', 'kd_propinsi');
     }
 
     // Relasi ke model Kota
     public function kota()
     {
         return $this->belongsTo(Kota::class, 'kd_kota', 'kd_kota');
     }
 
     // Relasi ke model Sekolah
     public function jenisSekolah()
     {
         return $this->belongsTo(JenisSekolah::class, 'kd_jenis_sekolah', 'kd_jenis_sekolah');
     }
 
     public function jurusanSekolah()
     {
         return $this->belongsTo(JurusanSekolah::class, 'kd_jurusan_sekolah', 'kd_jurusan_sekolah');
     }
}
