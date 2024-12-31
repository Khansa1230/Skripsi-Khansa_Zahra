<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;
   
    protected $primaryKey = 'kd_fakultas'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_fakultas',
        'fakultas',
    ];

    // Definisikan relasi ke tabel Matakuliah
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kd_fakultas', 'kd_fakultas');
    } 

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'kd_fakultas', 'kd_fakultas');
    } 
}
