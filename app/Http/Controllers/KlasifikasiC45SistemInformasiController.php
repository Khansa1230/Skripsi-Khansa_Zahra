<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlasifikasiC45SistemInformasiController extends Controller
{
    public function klasifikasi_dan_evaluation_mahasiswa_sistem_informasi(Request $request)
    {
         // Ambil tahun dari database
         $years = DB::table('mahasiswa as m')
         ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
         ->groupBy('year')
         ->orderBy('year', 'DESC')
         ->get();
 
     // Ambil input dari pengguna
     $predictedLulus = $request->input('predicted_lulus'); // Ambil input dari pengguna
     $year = $request->input('year', 'Semua'); 
 
     // Inisialisasi variabel untuk hasil query dan perhitungan entropi
     $result1 = [];
     $totalMahasiswa = 0;
     $entropyTotal1 = 0;
        // Menjalankan query untuk mengambil data dari database
        $query10 = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->join('jenis_sekolah_mahasiswa_baru as js', 'js.kd_jenis_sekolah', '=', 'm.kd_jenis_sekolah')
            ->select(
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
                "),
            )
            ->where('j.jurusan', '=', 'Sistem Informasi');
           
         // Query 1: Mengambil data jenis status dan jumlah mahasiswa
         $query1 = DB::table('matakuliah as mk')
         ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
         ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
         ->select(
             DB::raw("
                 CASE 
                     WHEN mk.status IN ('Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 'Belum Lulus'
                     WHEN mk.status = 'Lulus' THEN 'Lulus'
                     ELSE 'Belum Lulus'
                 END AS jenis_status
             "),
             DB::raw("COUNT(m.nim) AS total_mahasiswa")
         )
         ->where('j.jurusan', 'Sistem Informasi')
         ->groupBy('jenis_status');
 
         // Query 2: Mengambil data status mahasiswa (Aktif, Lulus, dsb)
         $query2= DB::table('matakuliah as mk')
             ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->select(
                 'mk.status', // Tambahkan status ke dalam select
                 DB::raw("COUNT(mk.nim) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy('mk.status')
             ->orderBy('mk.status');

             $query3 = DB::table('mahasiswa as m')
             ->join('matakuliah as mk', 'mk.nim', '=', 'm.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->join('jenis_sekolah_mahasiswa_baru as js', 'm.kd_jenis_sekolah', '=', 'js.kd_jenis_sekolah')
             ->select(
                 DB::raw("IF(js.jenis_sekolah = '' OR js.jenis_sekolah IS NULL, 'Tidak Terdata', js.jenis_sekolah) AS jenis_sekolah"),
                 DB::raw("COUNT(m.nim) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy('jenis_sekolah')  // Perbaikan: Gunakan js.jenis_sekolah untuk konsistensi
             ->orderBy('jenis_sekolah'); // Perbaikan: Urutkan berdasarkan jenis_sekolah yang benar
 
         
         $query4 = DB::table('matakuliah as mk')
             ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->select(
                 DB::raw("CASE 
                     WHEN mk.tanggal_lulus IS NULL THEN 'Belum Daftar'
                     ELSE YEAR(mk.tanggal_lulus)
                 END AS tahun_lulus"),
                 
                 DB::raw("COUNT(*) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy('tahun_lulus')
             ->orderByRaw("FIELD(tahun_lulus, 'Belum Daftar') DESC, tahun_lulus ASC"); // Atur urutan dengan 'Belum Daftar' di atas
         
 
         // Query 3
         $query5 = DB::table('matakuliah as mk')
             ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->select(
                 DB::raw("CASE 
                     WHEN mk.sks >= 144 THEN 'Memenuhi'
                     ELSE 'Belum Memenuhi'
                 END AS kategori_sks"),
                 DB::raw("COUNT(*) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy('kategori_sks')  // Menggunakan alias kategori_sks yang sudah didefinisikan di select
             ->orderBy('kategori_sks');  // Menggunakan alias kategori_sks yang sudah didefinisikan di select
 
 
         $query6 = DB::table('matakuliah as mk')
             ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->select(
                 DB::raw("CASE 
                     WHEN REPLACE(mk.ipk, ',', '.') >= 2.75 AND REPLACE(mk.ipk, ',', '.') < 3.00 THEN 'Memuaskan'
                     WHEN REPLACE(mk.ipk, ',', '.') >= 3.00 AND REPLACE(mk.ipk, ',', '.') < 3.50 THEN 'Sangat Memuaskan'
                     WHEN REPLACE(mk.ipk, ',', '.') >= 3.50 THEN 'Pujian'
                     ELSE 'Perbaiki'
                 END AS kategori_ipk"),
                 DB::raw("COUNT(*) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy(DB::raw("kategori_ipk")) 
             ->orderBy('kategori_ipk');
 
         $query7= DB::table('matakuliah as mk')
             ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->select(
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
                 
                 DB::raw("COUNT(mk.nim) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
                 
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy('kategori_pkl') // Menggunakan DB::raw untuk grup
             ->orderBy('kategori_pkl'); // Jangan lupa untuk mengeksekusi query
 
         $query8 = DB::table('matakuliah as mk')
             ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->select(
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
                 
                 DB::raw("COUNT(mk.nim) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy('kategori_kkn') 
             ->orderBy('kategori_kkn'); 
 
         $query9 = DB::table('matakuliah as mk')
             ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
             ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
             ->select(
                 DB::raw("CASE 
                     WHEN mk.seminar IS NULL OR mk.seminar = '' THEN 
                         CASE 
                             WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                             ELSE 'Belum Daftar'
                         END
                     ELSE CONCAT('Semester ', mk.seminar)
                 END AS kategori_seminar"),
                 
                 DB::raw("COUNT(mk.nim) AS total_mahasiswa"),
                 DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                 DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
             )
             ->where('j.jurusan', 'Sistem Informasi')
             ->groupBy('kategori_seminar') // Grouping berdasarkan kategori_seminar
             ->orderBy('kategori_seminar');  // Mengurutkan berdasarkan kategori_seminar
             

        // Filter by year if provided
        if ($year && $year !== 'Semua') {
            $query1->whereYear('m.tahun_angkatan', '=', $year);
            $query2->whereYear('m.tahun_angkatan', '=', $year);
            $query3->whereYear('m.tahun_angkatan', '=', $year);
            $query4->whereYear('m.tahun_angkatan', '=', $year);
            $query5->whereYear('m.tahun_angkatan', '=', $year);
            $query6->whereYear('m.tahun_angkatan', '=', $year);
            $query7->whereYear('m.tahun_angkatan', '=', $year);
            $query8->whereYear('m.tahun_angkatan', '=', $year);
            $query9->whereYear('m.tahun_angkatan', '=', $year);
            $query10->whereYear('m.tahun_angkatan', '=', $year);


        }

        
    
        // Eksekusi query untuk mengambil hasil
        $result1 =  $query1->get();
        $result2 = $query2->get();
        $result3 = $query3->get();
        $result4 = $query4->get();
        $result5 = $query5->get();
        $result6 = $query6->get();
        $result7 = $query7->get();
        $result8 = $query8->get();
        $result9 = $query9->get();
        $query10 = $query10->get();
        // Hitung jumlah mahasiswa yang lulus dan status lainnya
        $statusCount1 = [];
        foreach ($result1 as $item) {
            $statusCount1[$item->jenis_status] = $item->total_mahasiswa;
        }
    
        // Hitung entropi untuk result1
        $total1 = array_sum($statusCount1);
        foreach ($statusCount1 as $count) {
            if ($count > 0) {
                $probability = $count / $total1;
                $entropyTotal1 -= $probability * log($probability, 2); // Rumus Entropi
            }
        }
        //dd($result1);
        // Pastikan predictedLulus memiliki nilai integer
        if (is_null($predictedLulus)) {
            $predictedLulus = 0; // Set ke 0 jika null
        }
        
        // Hitung evaluasi matriks (jika ada fungsi ini)
        $evaluation = $this->calculateEvaluationMatriks($statusCount1, $total1, (int)$predictedLulus);
    
        // Hitung entropi berbobot total (jika ada fungsi ini)
        $total2 = $this->calculateTotalWeightedEntropy($result2, $entropyTotal1, $total1, 'status');
        $total3 = $this->calculateTotalWeightedEntropy($result3, $entropyTotal1, $total1, 'jenis_sekolah');
        $total4 = $this->calculateTotalWeightedEntropy($result4, $entropyTotal1, $total1, 'tahun_lulus');
        $total5 = $this->calculateTotalWeightedEntropy($result5, $entropyTotal1, $total1, 'kategori_sks');
        $total6 = $this->calculateTotalWeightedEntropy($result6, $entropyTotal1, $total1,'kategori_ipk');
        $total7 = $this->calculateTotalWeightedEntropy($result7, $entropyTotal1, $total1, 'kategori_pkl');
        $total8 = $this->calculateTotalWeightedEntropy($result8, $entropyTotal1, $total1, 'kategori_kkn');
        $total9 = $this->calculateTotalWeightedEntropy($result9, $entropyTotal1, $total1, 'kategori_seminar');
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

        //dd($query10, $data);

        // Menyimpan data ke file JSON untuk digunakan di Python
        $jsonFilePath = 'C:\\xampp\\htdocs\\skripsi\\app\\python_scripts\\sistem_informasi\\data.json';
        file_put_contents($jsonFilePath, json_encode($data));
 // Menyimpan informasi kol om yang terkait dengan setiap kategori
 $columnMapping = [
    'kategori_sks' => 'Satuan Kredit Skor',
    'kategori_ipk' => 'Indeks Prestasi Kumulatif',
    'kategori_pkl' => 'Praktek Kerja Lapangan', // atau kolom lain yang relevan
    'kategori_kkn' => 'Kuliah Kerja Nyata',
    'kategori_seminar' => 'Seminar',
];

// Menjalankan skrip Python
$pythonScriptPath = 'C:\\xampp\\htdocs\\skripsi\\app\\python_scripts\\sistem_informasi\\decision_tree_visualization.py';
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
return view('algoritma.klasifikasi_c45_dan_prediksi_matakuliah_sistem_informasi_mahasiswa', compact('outputData', 'total1', 'columnMapping','years', 'predictedLulus', 'year', 'result1', 'total2', 
        'total3', 'total4', 'total5', 'total6', 'total7', 'total8', 'total9', 'entropyTotal1', 'evaluation', 'totalMahasiswa'));
    }

    public function calculateEntropy(object $item, float $entropyTotal1, float $total1): array {
        $data2 = [
            'total_mahasiswa' => $item->total_mahasiswa,
            'belum_lulus' => $item->belum_lulus,
            'lulus' => $item->lulus
        ];

        // Entropy Calculation
        $entropy2 = 0;

        foreach ($data2 as $key => $value) {
            if ($key != 'total_mahasiswa' && $value > 0) {
                $probability = $value / $item->total_mahasiswa;
                $entropy2 -= $probability * log($probability, 2); // Rumus Entropi
            }
        }

        // Weighted Entropy Calculation
        $probability_total = $data2['total_mahasiswa'] / $total1;
        $weighted_entropy = $probability_total * $entropy2;

        // Add entropy to data
        $data2['entropy'] = $entropy2;
        $data2['probability_total'] = $probability_total;
        $data2['weighted_entropy'] = $weighted_entropy;

        return $data2;
    }

    public function calculateTotalWeightedEntropy($items, $entropyTotal1, $total1, $key) {
        $total_weighted_entropy = 0;
        $results = [];
        
        // Hitung weighted entropy untuk setiap item
        foreach ($items as $item) {
            $result = $this->calculateEntropy($item, $entropyTotal1, $total1);
            
            // Ganti kategori dengan nilai dari $item->$key
            $kategori = $item->$key; // Simpan nilai kategori
            $result['kategori'] = $kategori; // Mengganti kategori dengan nilai dari $item->$key
            $total_weighted_entropy += $result['weighted_entropy'];
            
            // Simpan hasil dengan kategori sebagai kunci
            $results[$kategori] = $result; // Ganti indeks dengan kategori
        }
        
        // Hitung gain berdasarkan total weighted entropy
        $gainC45 = $entropyTotal1 - $total_weighted_entropy;
        
        // Tambahkan total weighted entropy dan gain ke hasil
        foreach ($results as &$result) {
            // Hapus elemen 'kategori'
            unset($result['kategori']); // Menghapus elemen 'kategori'
            
            $result['total_weighted_entropy'] = $total_weighted_entropy;
            $result['gain_45'] = $gainC45; // Menambahkan gain_45 ke setiap item
        }
        
        return $results;
    }

    public function calculateEvaluationMatriks(array $statusCount1, int $total1, int $predictedLulus): array
    {
        // Data evaluasi awal
        $data1 = [
            'total_mahasiswa' => $total1,
            'belum_lulus' => $statusCount1['Belum Lulus'] ?? 0,
            'lulus' => $statusCount1['Lulus'] ?? 0,
        ];

        // Inisialisasi variabel TP, TN, FN, FP
        $TP = $data1['lulus']; // True Positive
        $TN = $data1['belum_lulus']; // True Negative
        $FN = 0;
        $FP = 0;

        // Hitung FN dan FP
        if ($predictedLulus < $TP) {
            $FN = $TP - $predictedLulus; // False Negative
        } elseif ($predictedLulus > $TP) {
            $FP = $predictedLulus - $TP; // False Positive
        }

        // Hitung akurasi
        $accuracy = ($TP + $TN) > 0 ? ($TP + $TN) / ($TP + $TN + $FP + $FN) : 0;
        // Hitung Presisi
        $precision = ($TP) > 0 ? ($TP) / ($TP +  $FP ) : 0;
        // Hitung Recall
        $recall = ($TP) > 0 ? ($TP) / ($TP +  $FN ) : 0;
        // Hitung F1-Score
        $f1_score = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0;
        // Tambahkan hasil evaluasi ke data
        $data1['TP'] = $TP;
        $data1['TN'] = $TN;
        $data1['FN'] = $FN;
        $data1['FP'] = $FP;
        $data1['accuracy'] = $accuracy;
        $data1['precision'] = $precision;
        $data1['recall'] = $recall;
        $data1['f1_score'] = $f1_score;

        return $data1;
    }
}
