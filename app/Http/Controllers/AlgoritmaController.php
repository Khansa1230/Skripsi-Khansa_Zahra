<?php

namespace App\Http\Controllers;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlgoritmaController extends Controller
{
    public function index(){
        return view ('algoritma.algoritma_c45');
    }

    
    public function klasifikasi_dan_evaluation_mahasiswa_teknik_informatika(Request $request)
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

    // Inisialisasi variabel untuk hasil query
    $result1 = [];

    // Query 1: Mengambil data jenis status dan jumlah mahasiswa
    $query1 = DB::table('matakuliah as mk')
        ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
        ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
        ->select(
            DB::raw("CASE 
                    WHEN mk.status IN ('Aktif', 'Cuti', 'Drop Out', 'Mengundurkan Diri') THEN 'Belum Lulus' 
                    WHEN mk.status = 'Lulus' THEN 'Lulus'
                    ELSE 'Lulus' END AS jenis_status"),
            DB::raw("COUNT(m.nim) AS total_mahasiswa")
        )
        ->where('j.jurusan', 'Teknik Informatika');

    // Filter by year if provided
    if ($year && $year !== 'Semua') {
        $query1->whereYear('m.tahun_angkatan', '=', $year);
    }

    // Eksekusi query untuk mengambil hasil
    $result1 = $query1->groupBy('jenis_status')->orderBy('jenis_status')->get();

    $statusCount1 = [];
    $entropyTotal1 = 0; // Inisialisasi dengan 0

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

    // Pastikan predictedLulus memiliki nilai integer
    if (is_null($predictedLulus)) {
        $predictedLulus = 0; // Set ke 0 jika null
    }

    // Kirim semua variabel ke view
    return view('algoritma.klasifikasi_c45_dan_prediksi_matakuliah_teknik_informatika_mahasiswa', compact('years', 'total1', 'predictedLulus', 'year', 'result1'));
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
