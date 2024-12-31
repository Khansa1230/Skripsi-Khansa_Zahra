<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtamaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun dari database
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Ambil input dari pengguna
        $year = $request->input('year', 'Semua'); 
    
        // Menjalankan query untuk mengambil data dari database
        $query10 = DB::table('mahasiswa as m')
            ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->join('jenis_sekolah_mahasiswa_baru as js', 'js.kd_jenis_sekolah', '=', 'm.kd_jenis_sekolah')
            ->select(
                'm.tahun_angkatan', // Menambahkan tahun_angkatan ke dalam select
                DB::raw("CASE 
                            WHEN mk.sks >= 144 THEN 'Memenuhi'
                            ELSE 'Belum Memenuhi'
                        END AS kategori_sks"),
                DB::raw("CASE 
                    WHEN REPLACE(mk.ipk, ',', '.') >= 2.75 AND REPLACE(mk.ipk, ',', '.') < 3.00 THEN 'Memuaskan'
                    WHEN REPLACE(mk.ipk, ',', '.') >= 3.00 AND REPLACE(mk.ipk, ',', '.') < 3.50 THEN 'Sangat Memuaskan'
                    WHEN REPLACE(mk.ipk, ',', '.') >= 3.50 THEN 'Pujian'
                    WHEN REPLACE(mk.ipk, ',', '.') < 2.75 THEN 'Perbaiki'
                END AS kategori_ipk"),
                DB::raw("CASE 
                    WHEN mk.praktek_kerja_lapangan_pkl IS NOT NULL AND mk.praktek_kerja_lapangan_pkl != '' 
                         THEN CONCAT('Semester ', mk.praktek_kerja_lapangan_pkl)
                    WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != '' 
                         THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                    WHEN mk.praktek_kerja_lapangan_pkl IS NULL AND mk.kuliah_kerja_lapangan IS NULL 
                         THEN NULL
                    ELSE 
                        CASE 
                            WHEN mk.status = 'Lulus' THEN 'Lulus Tidak Terdata'
                            ELSE 'Belum Daftar'
                        END
                END AS kategori_pkl"),
                DB::raw("CASE 
                    WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                        THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                    WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != '' 
                        THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                    WHEN mk.kuliah_kerja_nyata IS NULL AND mk.kuliah_kerja_nyata_kkn IS NULL 
                        THEN NULL
                    ELSE 
                        CASE 
                            WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                            ELSE 'Belum Daftar'
                        END
                END AS kategori_kkn"),
                DB::raw("CASE 
                    WHEN mk.seminar IS NULL OR mk.seminar = '' THEN 
                        CASE 
                            WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                            ELSE 'Belum Daftar'
                        END
                    ELSE CONCAT('Semester ', mk.seminar)
                END AS kategori_seminar"),
                DB::raw("
                CASE 
                    WHEN mk.status IN ('Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 'Belum Lulus'
                    WHEN mk.status = 'Lulus' THEN 'Lulus'
     ELSE 'Belum Lulus'
                END AS kategori
                ")
            )
            ->where('j.jurusan', '=', 'Teknik Informatika');
    
        // Filter by year if provided
        if ($year && $year !== 'Semua') {
            $query10->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        $query10 = $query10->get(); // Ambil hasil query setelah filter
    
        // Debugging untuk memeriksa data yang diambil
         //dd($query10, $year);
    
        // Mengonversi hasil query menjadi format yang bisa digunakan oleh Python
        $data = [];
        foreach ($query10 as $row) {
            $data[] = [
                'kategori_sks' => $row->kategori_sks,
                'kategori_ipk' => $row->kategori_ipk,
                'kategori_pkl' => $row->kategori_pkl,
                'kategori_kkn' => $row->kategori_kkn,
                'kategori_seminar' => $row->kategori_seminar,
                'kategori' => $row->kategori,
            ];
        }
    
        // Menyimpan data ke file JSON untuk digunakan di Python
        $jsonFilePath = 'C:\\xampp\\htdocs\\skripsi\\app\\python_scripts\\data.json';
        file_put_contents($jsonFilePath, json_encode($data));
    
        // Menyimpan informasi kolom yang terkait dengan setiap kategori
        $columnMapping = [
            'kategori_sks' => 'sks',
            'kategori_ipk' => 'ipk',
            'kategori_pkl' => 'praktek_kerja_lapangan_pkl',
            'kategori_kkn' => 'kuliah_kerja_nyata',
            'kategori_seminar' => 'seminar',
        ];
    
        // Menjalankan skrip Python
        $pythonScriptPath = 'C:\\xampp\\htdocs\\skripsi\\app\\python_scripts\\decision_tree_visualization.py';
        $output = shell_exec("python \"$pythonScriptPath\"");
    
        // Decode output JSON
        $result = json_decode($output, true);
    
        // Pastikan hasilnya adalah array dan memiliki kunci yang benar
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Jika terjadi kesalahan saat decoding JSON
            $outputData = [
                'tree_text' => 'Gagal mengurai output dari skrip Python.',
                'first_true_path' => [],
            ];
        } else {
            // Ambil data dari hasil
            $treeText = isset($result['tree_text']) ? $result['tree_text'] : 'Tidak ada teks pohon keputusan.';
            $firstTruePath = isset($result['first_true_path']) ? $result['first_true_path'] : [];
    
            // Mengatur output sebagai array
            $outputData = [
                'tree_text' => $treeText,
                'first_true_path' => $firstTruePath,
            ];
        }
    
        // Mengembalikan view dengan data dan pemetaan kolom
        return view('dashboard.utama', compact('years', 'year', 'outputData', 'columnMapping'));
    }
}