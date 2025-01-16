<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    // Nama tabel dalam basis data
    protected $table = 'matakuliah';

    // Primary key tabel
    protected $primaryKey = 'nim';

    // Primary key tidak menggunakan auto-increment
    public $incrementing = false;

    // Tipe data primary key adalah string
    protected $keyType = 'string';

    // Menonaktifkan timestamps (created_at, updated_at)
    public $timestamps = false;

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'nim',
        'nama',
        'kelamin',
        'tahun_angkatan',
        'status',
        'tahun_akademik',
        'kd_fakultas',
        'fakultas',
        'kd_jurusan',
        'jurusan',
        'geologi_lapangan',
        'kuliah_kerja_lapangan',
        'kuliah_kerja_nyata',
        'kuliah_kerja_nyata_kkn',
        'kuliah_lapangan',
        'kuliah_lapangan_1',
        'kuliah_lapangan_2',
        'praktek_kerja_lapangan',
        'praktek_kerja_lapangan_pkl',
        'seminar',
        'seminar_hasil',
        'seminar_hasil_penelitian',
        'seminar_proposal',
        'seminar_skripsi',
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

    // Relasi ke model Mahasiswa (jika ada)
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'nim');
    }
}
