<?php

namespace App\Http\Controllers;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaBiologiController extends Controller
{
    public function jumlah_mahasiswa_kelamin_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun, jurusan, dan status dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'mk.nim', '=', 'm.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                'm.kelamin',
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            )
            ->where('j.jurusan', '=', 'Biologi');
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Penerapan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
        }
    
        // Kelompokkan data berdasarkan kelamin
        $query = $query->groupBy('m.kelamin')
                       ->orderBy('m.kelamin')
                       ->get();
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_kelamin_mahasiswa', compact('years',  'allStatus',  'selectedStatus', 'query'));
    }
    
    public function jumlah_mahasiswa_status_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun dan jurusan dari request
        $year = $request->input('year');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('matakuliah as mk')
        ->join('mahasiswa as m', 'mk.nim', '=', 'm.nim')
        ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                'mk.status',
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            )
            ->where('j.jurusan', '=', 'Biologi');
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Kelompokkan data berdasarkan status
        $query = $query->groupBy('mk.status')
                       ->orderBy('mk.status')
                       ->get();
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_status_mahasiswa', compact('years',  'allStatus',  'query'));
    }

    public function jumlah_mahasiswa_jurusan_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun dan jurusan dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('mahasiswa as m')
            ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
            ->select(
                'mk.jurusan',
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            )
            ->where('j.jurusan', '=', 'Biologi');
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Penerapan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
        }
    
        // Kelompokkan data berdasarkan jurusan
        $query = $query->groupBy('mk.jurusan')
                       ->orderBy('mk.jurusan')
                       ->get();
        //dd($query);
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_jurusan_mahasiswa', compact('years', 'allStatus', 'selectedStatus', 'query'));
    }
    
    public function jumlah_mahasiswa_jenis_seleksi_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun, jurusan, dan status dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'mk.nim', '=', 'm.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->join('jenisseleksi as js', 'm.kd_jenis_seleksi', '=', 'js.kd_jenis_seleksi')
            ->select(
                'js.jenis_seleksi',
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            )
            ->where('j.jurusan', '=', 'Biologi');
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Penerapan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
        }

        // Kelompokkan data berdasarkan kelamin
        $query = $query->groupBy('js.jenis_seleksi')
                       ->orderBy('js.jenis_seleksi')
                       ->get();
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_seleksi_mahasiswa', compact('years',  'allStatus',  'selectedStatus', 'query'));
    }
    
    public function jumlah_mahasiswa_propinsi_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun, jurusan, dan status dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'mk.nim', '=', 'm.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->join('propinsi as p', 'm.kd_propinsi', '=', 'p.kd_propinsi')
            ->select(
                //'p.propinsi',
                DB::raw('IF(p.propinsi = "", "Tidak Terdata", p.propinsi) as propinsi'), // Mengganti propinsi kosong dengan "Tidak Terdata"
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            )
            ->where('j.jurusan', '=', 'Biologi');
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Penerapan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
        }
        // Kelompokkan data berdasarkan kelamin
        $query = $query->groupBy('p.propinsi')
                       ->orderBy('p.propinsi')
                       ->get();

        //dd($query);
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_propinsi_mahasiswa', compact('years',  'allStatus',  'selectedStatus', 'query'));
    }

    public function jumlah_mahasiswa_kota_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
 
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun, jurusan, status, dan propinsi dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
    
        // Memulai query untuk mengambil propinsi berdasarkan filter
        $allPropinsi = DB::table('propinsi as p')
            ->join('kota as k', 'k.kd_propinsi', '=', 'p.kd_propinsi')
            ->join('mahasiswa as m', 'm.kd_kota', '=', 'k.kd_kota')
            ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
            ->select('p.propinsi')
            ->distinct();
    
        // Menerapkan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $allPropinsi->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Menerapkan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $allPropinsi->where('mk.status', '=', $selectedStatus);
        }
    
        // Mendapatkan hasil propinsi sebagai collection
        $allPropinsi = $allPropinsi->get(); // Hasil query disimpan di $allPropinsi
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_kota_mahasiswa', compact('years',  'allStatus', 'allPropinsi',  'selectedStatus'));
    }
    
    
    public function jumlah_mahasiswa_jenis_sekolah_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun, jurusan, dan status dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'mk.nim', '=', 'm.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->join('jenis_sekolah_mahasiswa_baru as js', 'm.kd_jenis_sekolah', '=', 'js.kd_jenis_sekolah')
            ->select(
                //'p.propinsi',
                DB::raw('IF(js.jenis_Sekolah = "", "Tidak Terdata", js.jenis_sekolah) as jenis_sekolah'), // Mengganti propinsi kosong dengan "Tidak Terdata"
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            )
            ->where('j.jurusan', '=', 'Biologi');
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Penerapan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
        }

        // Kelompokkan data berdasarkan kelamin
        $query = $query->groupBy('js.jenis_sekolah')
                       ->orderBy('js.jenis_sekolah')
                       ->get();

        //dd($query);
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_sekolah_mahasiswa', compact('years',  'allStatus',  'selectedStatus', 'query'));
    }

    public function jumlah_mahasiswa_satuan_kredit_semester_biologi(Request $request) {
    
        // Mengambil tahun angkatan mahasiswa
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun, jurusan, dan status dari request
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $selectedStatus = $request->input('status');

         // Query untuk mendapatkan jumlah mahasiswa berdasarkan kategori SKS
         $query = DB::table('matakuliah as mk')
         ->join('mahasiswa as m', 'm.nim', '=', 'mk.nim')
         ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
         ->select(
             DB::raw("CASE 
                 WHEN mk.sks >= 144 THEN 'Memenuhi'
                 ELSE 'Belum Memenuhi'
             END AS kategori_sks"),
             DB::raw("COUNT(*) AS jumlah_mahasiswa")
         )
         ->where('j.jurusan', '=', 'Biologi')
         ->groupBy('kategori_sks')
         ->orderBy('kategori_sks');
    
        // Menyaring berdasarkan input (tahun, jurusan, dan status) jika tersedia
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
        }
    
        // Menyimpan hasil query untuk ditampilkan di view
        $query = $query->get();
        //dd($query);
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_satuan_kredit_semester_mahasiswa', compact('years', 'allStatus', 'selectedStatus', 'query'));
    }
    

    public function jumlah_mahasiswa_indeks_prestasi_kumulatif_biologi(Request $request) {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table('mahasiswa as m')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        // Mengambil semua status yang ada
        $allStatus = DB::table('matakuliah as mk')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->get();
    
        // Mengambil input tahun, jurusan, dan status dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
    
        // Memulai query dengan fokus pada mahasiswa
        $query = DB::table('matakuliah as mk')
            ->join('mahasiswa as m', 'mk.nim', '=', 'm.nim')
            ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                DB::raw("
                    CASE
                        WHEN mk.ipk >= '2,75' AND mk.ipk < '3,00' THEN 'Memuaskan'
                        WHEN mk.ipk >= '3,00' AND mk.ipk < '3,50' THEN 'Sangat Memuaskan'
                        WHEN mk.ipk >= '3,50' THEN 'Pujian'
                        ELSE 'Perbaiki'
                    END AS kategoriipk
                "),
                DB::raw('COUNT(m.nim) as jumlah_mahasiswa')
            )
            ->where('j.jurusan', '=', 'Biologi');
    
        // Penerapan filter tahun jika dipilih dan tidak sama dengan 'Semua'
        if ($year && $year !== 'Semua') {
            $query->whereYear('m.tahun_angkatan', '=', $year);
        }
    
        // Penerapan filter status jika dipilih dan tidak sama dengan 'Semua'
        if ($selectedStatus && $selectedStatus !== 'Semua') {
            $query->where('mk.status', '=', $selectedStatus);
        }

        // Kelompokkan data berdasarkan kelamin
        $query = $query->groupBy('kategoriipk')
                       ->orderBy('kategoriipk')
                       ->get();

        //dd($query);
    
        // Mengembalikan view dengan data yang diperlukan
        return view('biologi.mahasiswa.jenis_indeks_prestasi_kumulatif_mahasiswa', compact('years',  'allStatus',  'selectedStatus', 'query'));
    }

    
}
