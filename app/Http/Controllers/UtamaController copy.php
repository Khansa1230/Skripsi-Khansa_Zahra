<?php

namespace App\Http\Controllers;
use Phpml\Classification\DecisionTree;
use Phpml\Dataset\ArrayDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UtamaController extends Controller
{
    public function index2(){
        return view ('dashboard.dashboard');
    }

    public function index3(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua jurusan yang ada
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil semua propinsi yang ada, tanpa join ke tabel kota

        $allPropinsi = DB::table('propinsi as p')
            ->join('mahasiswa as m', 'm.kd_propinsi', '=', 'p.kd_propinsi') // Join ke tabel mahasiswa
            ->join('matakuliah as mk', 'mk.nim', '=', 'm.nim') // Join ke tabel matakuliah
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan') // Join ke tabel jurusan
            ->select(DB::raw('IF(p.propinsi = "", "Tidak Terdata", p.propinsi) as propinsi'))
            ->distinct(); // Menggunakan distinct jika ada kemungkinan data duplikat
        
    
    
    
        // Mengambil input tahun, jurusan, dan status dari request
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $selectedStatus = $request->input('status');
        $selectedPropinsi = $request->input('propinsi');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('mahasiswa as m')
            ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
            ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
            ->select(
                DB::raw('IF(k.kota = "", "Tidak Terdata", k.kota) as kota'), // Mengganti kota kosong dengan "Tidak Terdata"
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            );
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
            $allPropinsi->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Penerapan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
            $allPropinsi->where('mk.status', '=', $selectedStatus);
        }
        
    
        // Penerapan filter jurusan jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedJurusan && $selectedJurusan !== 'Semua') {
            $query->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                  ->where('j.jurusan', '=', $selectedJurusan);
            $allPropinsi->where('j.jurusan', '=', $selectedJurusan);
        }
    
        // Penerapan filter propinsi jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedPropinsi && $selectedPropinsi !== 'Semua') {
            $query->join('propinsi as p', 'k.kd_propinsi', '=', 'p.kd_propinsi')
                  ->where('p.propinsi', '=', $selectedPropinsi);
        }
    
        // Kelompokkan data berdasarkan kota
        $query = $query->groupBy('k.kota')
                       ->orderBy('k.kota')
                       ->get();

        // Ambil hasil dari allPropinsi setelah debug
        $allPropinsi = $allPropinsi->orderBy('propinsi')->get();
        //dd($allPropinsi);
    
        return view('dashboard.utama', compact('years', 'allJurusan', 'allStatus', 'allPropinsi', 'selectedJurusan', 'selectedStatus', 'selectedPropinsi', 'query', 'year'));
    }
    

    public function index1(Request $request)
{
    // Ambil data tahun angkatan
    $years = DB::table('mahasiswa as m')
        ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();
    

    $year = $request->input('year');
    $result = [];
    $prediction = null;
    $entropyTotal = 0;

    if ($year) {
        $query = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                DB::raw("CASE 
                    WHEN mk.status IN ('Aktif', 'Cuti') THEN 'Belum Lulus' 
                    WHEN mk.status IN ('Drop Out', 'Mengundurkan Diri') THEN 'Tidak Lulus' 
                    WHEN mk.status = 'Lulus' THEN 
                        CASE 
                            WHEN YEAR(mk.tanggal_lulus) - m.tahun_angkatan >= 3 AND YEAR(mk.tanggal_lulus) - m.tahun_angkatan < 4 THEN 'Lulus'
                            WHEN YEAR(mk.tanggal_lulus) - m.tahun_angkatan >= 4 THEN 'Telat Lulus'
                            ELSE 'Lulus'
                        END 
                    ELSE 'Lulus'
                END AS jenis_status"),
                DB::raw("COUNT(m.nim) AS total_mahasiswa")
            )
            ->where('j.jurusan', 'Teknik Informatika');

        // Tambahkan kondisi tahun jika tidak memilih 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }

        // Tambahkan groupBy dan orderBy sebelum eksekusi query
        $query->groupBy('jenis_status')->orderBy('jenis_status');

        $result = $query->get();

        // Hitung jumlah mahasiswa berdasarkan jenis_status
        $statusCount = [];
        foreach ($result as $item) {
            $statusCount[$item->jenis_status] = $item->total_mahasiswa;
        }

        // Hitung total mahasiswa
        $total = array_sum($statusCount);

        // Hitung Entropy
        $entropyTotal = 0;
        foreach ($statusCount as $count) {
            $probability = $count / $total;
            $entropyTotal -= $probability * log($probability, 2);
        }
        // dd($year);
        // Siapkan data untuk Decision Tree
        $data = [];
        $labels = [];
        foreach ($result as $item) {
            $data[] = [$item->total_mahasiswa]; // Fitur
            $labels[] = $item->jenis_status;    // Label
        }

        // Buat dataset dan latih model Decision Tree
        if (!empty($data)) {
            $dataset = new ArrayDataset($data, $labels);
            $classifier = new DecisionTree();
            $classifier->train($dataset->getSamples(), $dataset->getTargets());

            // Prediksi contoh status untuk input tertentu (misalnya, 30 mahasiswa)
            $prediction = $classifier->predict([30]);
        }
    }

    return view('dashboard.utama', compact('years', 'result', 'year', 'prediction', 'entropyTotal'));
}

public function index(Request $request)
{
    $years = DB::table('mahasiswa as m')
        ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();
    
    $predictedLulus = $request->input('predicted_lulus'); // Ambil input dari pengguna

    $year = $request->input('year');
    $result1 = [];
    $result2 = [];
    $entropyTotal1 = 0;
    $entropyTotal2 = 0;

    DB::statement("SET SESSION sql_mode = (SELECT REPLACE(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

    if ($year) {

        
        // Query 1
        $query1 = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                DB::raw("CASE 
                        WHEN mk.status IN ('Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 'Belum Lulus' 
                        WHEN mk.status = 'Lulus' THEN 'Lulus'
                        ELSE 'Lulus'  
                END AS jenis_status"),
                DB::raw("COUNT(m.nim) AS total_mahasiswa")
            )
            ->where('j.jurusan', 'Teknik Informatika');

        // Query 2
        $query2= DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                'mk.status', // Tambahkan status ke dalam select
                DB::raw("COUNT(mk.nim) AS total_mahasiswa"),
                DB::raw("COUNT(CASE WHEN mk.status IN ('Tidak Aktif', 'Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 1 END) AS belum_lulus"),
                DB::raw("COUNT(CASE WHEN mk.status = 'Lulus' THEN 1 END) AS lulus")
            )
            ->where('j.jurusan', 'Teknik Informatika')
            ->groupBy('mk.status')
            ->orderBy('mk.status');

            
        $query3 = DB::table('matakuliah as mk')
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
            ->where('j.jurusan', 'Teknik Informatika')
            ->groupBy('tahun_lulus')
            ->orderByRaw("FIELD(tahun_lulus, 'Belum Daftar') DESC, tahun_lulus ASC"); // Atur urutan dengan 'Belum Daftar' di atas
        

        // Query 3
        $query4 = DB::table('matakuliah as mk')
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
            ->where('j.jurusan', 'Teknik Informatika')
            ->groupBy('kategori_sks')  // Menggunakan alias kategori_sks yang sudah didefinisikan di select
            ->orderBy('kategori_sks');  // Menggunakan alias kategori_sks yang sudah didefinisikan di select


        $query5 = DB::table('matakuliah as mk')
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
            ->where('j.jurusan', 'Teknik Informatika')
            ->groupBy(DB::raw("kategori_ipk")) 
            ->orderBy('kategori_ipk');

        $query6= DB::table('matakuliah as mk')
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
            ->where('j.jurusan', 'Teknik Informatika')
            ->groupBy('kategori_pkl') // Menggunakan DB::raw untuk grup
            ->orderBy('kategori_pkl'); // Jangan lupa untuk mengeksekusi query

        $query7 = DB::table('matakuliah as mk')
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
            ->where('j.jurusan', 'Teknik Informatika')
            ->groupBy('kategori_kkn') 
            ->orderBy('kategori_kkn'); 

        $query8 = DB::table('matakuliah as mk')
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
            ->where('j.jurusan', 'Teknik Informatika')
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
        }

        // Execute the queries
        $result1 = $query1->groupBy('jenis_status')->orderBy('jenis_status')->get();
        $result2 = $query2->get();
        $result3 = $query3->get();
        $result4 = $query4->get();
        $result5 = $query5->get();
        $result6 = $query6->get();
        $result7 = $query7->get();
        $result8 = $query8->get();

        // Calculate entropy for result1
        $statusCount1 = [];
        foreach ($result1 as $item) {
            $statusCount1[$item->jenis_status] = $item->total_mahasiswa;
        }

        $total1 = array_sum($statusCount1);

        $entropyTotal1 = 0;
        foreach ($statusCount1 as $count) {
            if ($count > 0) {
                $probability = $count / $total1;
                $entropyTotal1 -= $probability * log($probability, 2); // Rumus Entropi
            }
        }

       // Validasi input predictedLulus
       if ($predictedLulus > $total1) {
        return redirect()->back()->withErrors([
            'predicted_lulus' => 'Data tidak valid: nilai lebih dari total mahasiswa.'
        ]);
    }

    $predictedLulus = max(0, (int)$predictedLulus); // Nilai minimal adalah 0

    // Hitung evaluasi matriks
    $evaluation = $this->calculateEvaluationMatriks($statusCount1, $total1, $predictedLulus);

        // Menghitung total weighted entropy untuk setiap hasil
        $total2 = $this->calculateTotalWeightedEntropy($result2, $entropyTotal1, $total1, 'status');
        $total3 = $this->calculateTotalWeightedEntropy($result3, $entropyTotal1, $total1, 'tahun_lulus');
        $total4 = $this->calculateTotalWeightedEntropy($result4, $entropyTotal1, $total1, 'kategori_sks');
        $total5 = $this->calculateTotalWeightedEntropy($result5, $entropyTotal1, $total1,'kategori_ipk');
        $total6 = $this->calculateTotalWeightedEntropy($result6, $entropyTotal1, $total1, 'kategori_pkl');
        $total7 = $this->calculateTotalWeightedEntropy($result7, $entropyTotal1, $total1, 'kategori_kkn');
        $total8 = $this->calculateTotalWeightedEntropy($result8, $entropyTotal1, $total1, 'kategori_seminar');
        // Debugging: Display entropy and gain results
        //dd($result1, $entropyTotal1,$result2, $total2, $total3, $total4, $total5, $total6, $total7);

    }

    return view('dashboard.utama', compact('years', 'result1', 'total2', 'total3', 'total4',
     'total5', 'total6', 'total7',  'total8', 'year', 'entropyTotal1', 'entropyTotal2', 'evaluation'));
}

// Fungsi untuk menghitung entropy dan gain
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
    
        // Tambahkan hasil evaluasi ke data
        $data1['TP'] = $TP;
        $data1['TN'] = $TN;
        $data1['FN'] = $FN;
        $data1['FP'] = $FP;
        $data1['accuracy'] = $accuracy;
    
        return $data1;
    }

}