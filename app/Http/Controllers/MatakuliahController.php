<?php

namespace App\Http\Controllers;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatakuliahController extends Controller
{
    public function index(){
        return view ('mahasiswa.matakuliah');
    }

    public function jumlah_mahasiswa_matakuliah_teknik_informatika(Request $request)
    {
        // Mengambil tahun angkatan mahasiswa (tanpa filter status)
        $years = DB::table ('mahasiswa as m')
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
            // Query untuk Kuliah Kerja Nyata (KKN)
            $kkn = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Teknik Informatika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdata'
                                ELSE 'Belum daftar'
                            END
                    END AS kkn,
                    COUNT(m.nim) as total_jumlah
                "))
                ->groupBy('kkn')
                ->orderBy('kkn');
    
            // Query untuk Praktek Kerja Lapangan (PKL)
            $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Teknik Informatika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.praktek_kerja_lapangan_pkl IS NOT NULL AND mk.praktek_kerja_lapangan_pkl != '' 
                            THEN CONCAT('Semester ', mk.praktek_kerja_lapangan_pkl)
                        WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                        WHEN mk.praktek_kerja_lapangan_pkl IS NULL AND mk.kuliah_kerja_lapangan IS NULL THEN NULL
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                                ELSE 'belum daftar'
                            END
                    END AS pkl,
                    COUNT(*) AS total_jumlah
                "))
                ->groupBy('pkl')
                ->orderBy('pkl');

    
            $seminar = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Teknik Informatika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.seminar IS NULL OR mk.seminar = '' THEN 
                            MAX(CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END)
                        ELSE CONCAT('Semester ', mk.seminar)
                    END AS seminar,
                    COUNT(m.nim) AS total_jumlah
                "))
                ->groupBy('seminar')
                ->orderBy('seminar');
            
    
            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'seminar') {
                $query = $seminar->get()->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }            
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_teknik_informatika_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }

    public function jumlah_mahasiswa_matakuliah_agribisnis(Request $request)
    {
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
            // Query untuk Kuliah Kerja Nyata (KKN)
            $kkn = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Agribisnis')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdata'
                                ELSE 'Belum daftar'
                            END
                    END AS kkn,
                    COUNT(m.nim) as total_jumlah
                "))
                ->groupBy('kkn')
                ->orderBy('kkn');
    
            // Query untuk Praktek Kerja Lapangan (PKL)
            $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Agribisnis')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.praktek_kerja_lapangan_pkl IS NOT NULL AND mk.praktek_kerja_lapangan_pkl != '' 
                            THEN CONCAT('Semester ', mk.praktek_kerja_lapangan_pkl)
                        WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                        WHEN mk.praktek_kerja_lapangan_pkl IS NULL AND mk.kuliah_kerja_lapangan IS NULL THEN NULL
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                                ELSE 'belum daftar'
                            END
                    END AS pkl,
                    COUNT(*) AS total_jumlah
                "))
                ->groupBy('pkl')
                ->orderBy('pkl');
        
    
            $seminar = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Agribisnis')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.seminar IS NULL OR mk.seminar = '' THEN 
                            MAX(CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' 
                            ELSE 'Belum terdaftar' END)
                        ELSE CONCAT('Semester ', mk.seminar)
                    END AS seminar,
                    COUNT(m.nim) AS total_jumlah
                "))
                ->groupBy('seminar')
                ->orderBy('seminar');
            
    
            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'seminar') {
                $query = $seminar->get()->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }            
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_agribisnis_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }
   
    public function jumlah_mahasiswa_matakuliah_biologi(Request $request)
    {
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
            // Query untuk Kuliah Kerja Nyata (KKN)
            $kkn = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Biologi')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdata'
                                ELSE 'Belum daftar'
                            END
                    END AS kkn,
                    COUNT(m.nim) as total_jumlah
                "))
                ->groupBy('kkn')
                ->orderBy('kkn');

            $seminar = DB::table(DB::raw("(SELECT 
                        CASE 
                            WHEN mk.seminar_hasil IS NOT NULL AND mk.seminar_hasil != '' 
                                THEN CONCAT('Semester ', mk.seminar_hasil)
                            WHEN mk.seminar IS NOT NULL AND mk.seminar != '' 
                                THEN CONCAT('Semester ', mk.seminar)
                            WHEN mk.seminar_hasil IS NULL AND mk.seminar IS NULL 
                                THEN 'belum daftar'
                            ELSE 
                                CASE 
                                    WHEN mk.status = 'Lulus' THEN 'lulus tidak terdaftar'
                                    ELSE 'belum terdaftar'
                                END
                        END AS seminar
                    FROM mahasiswa AS m
                    JOIN matakuliah AS mk ON m.nim = mk.nim
                    JOIN jurusan AS j ON mk.kd_jurusan = j.kd_jurusan
                    WHERE j.jurusan = 'Biologi'
                ) as subquery"))
                ->select(DB::raw("seminar, COUNT(*) AS total_jumlah"))
                ->groupBy('seminar')
                ->orderBy('seminar');
            

                $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Biologi')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_lapangan IS NULL OR mk.kuliah_kerja_lapangan = '' THEN 
                            CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' 
                                 ELSE 'Belum terdaftar' 
                            END
                        ELSE CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                    END AS pkl
                "))
                ->groupBy('pkl')
                ->orderBy('pkl')
                ->selectRaw('COUNT(m.nim) AS total_jumlah');

            $proposal = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Biologi')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.seminar_proposal IS NULL OR mk.seminar_proposal = '' THEN 
                            CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' 
                                 ELSE 'Belum terdaftar' 
                            END
                        ELSE CONCAT('Semester ', mk.seminar_proposal)
                    END AS proposal
                "))
                ->groupBy('proposal')
                ->orderBy('proposal')
                ->selectRaw('COUNT(m.nim) AS total_jumlah');
            


            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
                $proposal->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortBy(function($value, $key) {
                    return $key; // Mengurutkan berdasarkan kunci
                })->toArray(); // Ubah ke array jika diperlukan
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortBy(function($value, $key) {
                    return $key; // Mengurutkan berdasarkan kunci
                })->toArray();
            } elseif ($selectedMatakuliah === 'seminar') {
                $seminarData = $seminar->get();
                //dd($seminarData); // Memeriksa hasil query
                $query = $seminarData->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortBy(function($value, $key) {
                    // Ekstrak nomor semester untuk pengurutan
                    preg_match('/\d+/', $key, $matches);
                    return isset($matches[0]) ? (int)$matches[0] : 0; // Kembalikan nomor semester untuk pengurutan
                })->toArray();
            } elseif ($selectedMatakuliah === 'proposal') {
                $query = $proposal->get()->mapWithKeys(function ($item) {
                    return [$item->proposal => $item->total_jumlah];
                })->sortBy(function($value, $key) {
                    return $key; // Mengurutkan berdasarkan kunci
                })->toArray();
            }
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_biologi_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }
    public function jumlah_mahasiswa_matakuliah_fisika(Request $request)
    {
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
            // Query untuk Kuliah Kerja Nyata (KKN)
            $kkn = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Fisika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdata'
                                ELSE 'Belum daftar'
                            END
                    END AS kkn,
                    COUNT(m.nim) as total_jumlah
                "))
                ->groupBy('kkn')
                ->orderBy('kkn');
    
            // Query untuk Praktek Kerja Lapangan (PKL)
            $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Fisika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.praktek_kerja_lapangan_pkl IS NOT NULL AND mk.praktek_kerja_lapangan_pkl != '' 
                            THEN CONCAT('Semester ', mk.praktek_kerja_lapangan_pkl)
                        WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                        WHEN mk.praktek_kerja_lapangan_pkl IS NULL AND mk.kuliah_kerja_lapangan IS NULL THEN NULL
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                                ELSE 'belum daftar'
                            END
                    END AS pkl,
                    COUNT(*) AS total_jumlah
                "))
                ->groupBy('pkl')
                ->orderBy('pkl');

    
            $seminar = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Fisika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.seminar IS NULL OR mk.seminar = '' THEN 
                            MAX(CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END)
                        ELSE CONCAT('Semester ', mk.seminar)
                    END AS seminar,
                    COUNT(m.nim) AS total_jumlah
                "))
                ->groupBy('seminar')
                ->orderBy('seminar');
            
    
            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'seminar') {
                $query = $seminar->get()->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }            
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_fisika_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }

    public function jumlah_mahasiswa_matakuliah_kimia(Request $request)
    {
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
            // Query untuk Kuliah Kerja Nyata (KKN)
            $kkn = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Kimia')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdata'
                                ELSE 'Belum daftar'
                            END
                    END AS kkn,
                    COUNT(m.nim) as total_jumlah
                "))
                ->groupBy('kkn')
                ->orderBy('kkn');
    
            // Query untuk Praktek Kerja Lapangan (PKL)
            $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Kimia')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.praktek_kerja_lapangan_pkl IS NOT NULL AND mk.praktek_kerja_lapangan_pkl != '' 
                            THEN CONCAT('Semester ', mk.praktek_kerja_lapangan_pkl)
                        WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                        WHEN mk.praktek_kerja_lapangan_pkl IS NULL AND mk.kuliah_kerja_lapangan IS NULL THEN NULL
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                                ELSE 'belum daftar'
                            END
                    END AS pkl,
                    COUNT(*) AS total_jumlah
                "))
                ->groupBy('pkl')
                ->orderBy('pkl');

            $seminar = DB::table(DB::raw("(SELECT 
                    CASE 
                        WHEN mk.seminar_hasil IS NOT NULL AND mk.seminar_hasil != '' 
                            THEN CONCAT('Semester ', mk.seminar_hasil)
                        WHEN mk.seminar IS NOT NULL AND mk.seminar != '' 
                            THEN CONCAT('Semester ', mk.seminar)
                        WHEN mk.seminar_hasil IS NULL AND mk.seminar IS NULL 
                            THEN 'belum daftar'
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdaftar'
                                ELSE 'belum terdaftar'
                            END
                    END AS seminar
                FROM mahasiswa AS m
                JOIN matakuliah AS mk ON m.nim = mk.nim
                JOIN jurusan AS j ON mk.kd_jurusan = j.kd_jurusan
                WHERE j.jurusan = 'Kimia'
            ) as subquery"))
            ->select(DB::raw("seminar, COUNT(*) AS total_jumlah"))
            ->groupBy('seminar')
            ->orderBy('seminar');
            
            
    
            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'seminar') {
                $query = $seminar->get()->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }            
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_kimia_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }

    public function jumlah_mahasiswa_matakuliah_matematika(Request $request)
    {
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
            // Query untuk Kuliah Kerja Nyata (KKN)
            $kkn = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Matematika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdata'
                                ELSE 'Belum daftar'
                            END
                    END AS kkn,
                    COUNT(m.nim) as total_jumlah
                "))
                ->groupBy('kkn')
                ->orderBy('kkn');
    
            // Query untuk Praktek Kerja Lapangan (PKL)
            $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Matematika')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.praktek_kerja_lapangan_pkl IS NOT NULL AND mk.praktek_kerja_lapangan_pkl != '' 
                            THEN CONCAT('Semester ', mk.praktek_kerja_lapangan_pkl)
                        WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                        WHEN mk.praktek_kerja_lapangan_pkl IS NULL AND mk.kuliah_kerja_lapangan IS NULL THEN NULL
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                                ELSE 'belum daftar'
                            END
                    END AS pkl,
                    COUNT(*) AS total_jumlah
                "))
                ->groupBy('pkl')
                ->orderBy('pkl');

            $seminar = DB::table(DB::raw("(SELECT 
                    CASE 
                        WHEN mk.seminar_hasil IS NOT NULL AND mk.seminar_hasil != '' 
                            THEN CONCAT('Semester ', mk.seminar_hasil)
                        WHEN mk.seminar IS NOT NULL AND mk.seminar != '' 
                            THEN CONCAT('Semester ', mk.seminar)
                        WHEN mk.seminar_hasil IS NULL AND mk.seminar IS NULL 
                            THEN 'belum daftar'
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdaftar'
                                ELSE 'belum terdaftar'
                            END
                    END AS seminar
                FROM mahasiswa AS m
                JOIN matakuliah AS mk ON m.nim = mk.nim
                JOIN jurusan AS j ON mk.kd_jurusan = j.kd_jurusan
                WHERE j.jurusan = 'Matematika'
            ) as subquery"))
            ->select(DB::raw("seminar, COUNT(*) AS total_jumlah"))
            ->groupBy('seminar')
            ->orderBy('seminar');
            
            
    
            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'seminar') {
                $query = $seminar->get()->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }            
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_matematika_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }

    public function jumlah_mahasiswa_matakuliah_sistem_informasi(Request $request)
    {
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
            // Query untuk Kuliah Kerja Nyata (KKN)
            $kkn = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Sistem Informasi')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_kerja_nyata IS NOT NULL AND mk.kuliah_kerja_nyata != '' 
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        WHEN mk.kuliah_kerja_nyata_kkn IS NOT NULL AND mk.kuliah_kerja_nyata_kkn != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_nyata_kkn)
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdata'
                                ELSE 'Belum daftar'
                            END
                    END AS kkn,
                    COUNT(m.nim) as total_jumlah
                "))
                ->groupBy('kkn')
                ->orderBy('kkn');
    
            // Query untuk Praktek Kerja Lapangan (PKL)
            $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Sistem Informasi')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.praktek_kerja_lapangan_pkl IS NOT NULL AND mk.praktek_kerja_lapangan_pkl != '' 
                            THEN CONCAT('Semester ', mk.praktek_kerja_lapangan_pkl)
                        WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                        WHEN mk.praktek_kerja_lapangan_pkl IS NULL AND mk.kuliah_kerja_lapangan IS NULL THEN NULL
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                                ELSE 'belum daftar'
                            END
                    END AS pkl,
                    COUNT(*) AS total_jumlah
                "))
                ->groupBy('pkl')
                ->orderBy('pkl');

    
            $seminar = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Sistem Informasi')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.seminar IS NULL OR mk.seminar = '' THEN 
                            MAX(CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END)
                        ELSE CONCAT('Semester ', mk.seminar)
                    END AS seminar,
                    COUNT(m.nim) AS total_jumlah
                "))
                ->groupBy('seminar')
                ->orderBy('seminar');
            
    
            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'seminar') {
                $query = $seminar->get()->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }            
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_sistem_informasi_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }

    public function jumlah_mahasiswa_matakuliah_teknik_tambang(Request $request)
    {
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
    
        // Mengambil input tahun, status, dan matakuliah dari request
        $year = $request->input('year');
        $selectedStatus = $request->input('status');
        $selectedMatakuliah = $request->input('matakuliah');
    
        $query = [];
    
        if ($selectedMatakuliah) {
    
            // Query untuk Praktek Kerja Lapangan (PKL)
            $pkl = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Sistem Informasi')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.kuliah_lapangan IS NOT NULL AND mk.kuliah_lapangan != '' 
                            THEN CONCAT('Semester ', mk.kuliah_lapangan)
                        WHEN mk.kuliah_kerja_lapangan IS NOT NULL AND mk.kuliah_kerja_lapangan != ''
                            THEN CONCAT('Semester ', mk.kuliah_kerja_lapangan)
                        WHEN mk.kuliah_lapangan IS NULL AND mk.kuliah_kerja_lapangan IS NULL THEN NULL
                        ELSE 
                            CASE 
                                WHEN mk.status = 'Lulus' THEN 'lulus tidak terdata'
                                ELSE 'belum daftar'
                            END
                    END AS pkl,
                    COUNT(*) AS total_jumlah
                "))
                ->groupBy('pkl')
                ->orderBy('pkl');

            $geologi = DB::table(DB::raw("(SELECT 
                        CASE 
                            WHEN mk.geologi_lapangan IS NULL OR mk.geologi_lapangan = '' THEN 
                                CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END
                            ELSE CONCAT('Semester ', mk.geologi_lapangan)
                        END AS geologi
                    FROM mahasiswa AS m
                    JOIN matakuliah AS mk ON m.nim = mk.nim
                    JOIN jurusan AS j ON mk.kd_jurusan = j.kd_jurusan
                    WHERE j.jurusan = 'Teknik Pertambangan'
                ) as subquery"))
                ->select(DB::raw("geologi, COUNT(*) AS total_jumlah"))
                ->groupBy('geologi')
                ->orderBy('geologi');

                $lapangan1 = DB::table(DB::raw("(SELECT 
                CASE 
                    WHEN mk.kuliah_lapangan_1 IS NULL OR mk.kuliah_lapangan_1 = '' THEN 
                        CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END
                    ELSE CONCAT('Semester ', mk.kuliah_lapangan_1)
                END AS lapangan1
            FROM mahasiswa AS m
            JOIN matakuliah AS mk ON m.nim = mk.nim
            JOIN jurusan AS j ON mk.kd_jurusan = j.kd_jurusan
            WHERE j.jurusan = 'Teknik Pertambangan'
        ) as subquery"))
        ->select(DB::raw("lapangan1, COUNT(*) AS total_jumlah"))
        ->groupBy('lapangan1')
        ->orderBy('lapangan1');

        $lapangan2 = DB::table(DB::raw("(SELECT 
                CASE 
                    WHEN mk.kuliah_lapangan_2 IS NULL OR mk.kuliah_lapangan_2 = '' THEN 
                        CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END
                    ELSE CONCAT('Semester ', mk.kuliah_lapangan_2)
                END AS lapangan2
            FROM mahasiswa AS m
            JOIN matakuliah AS mk ON m.nim = mk.nim
            JOIN jurusan AS j ON mk.kd_jurusan = j.kd_jurusan
            WHERE j.jurusan = 'Teknik Pertambangan'
        ) as subquery"))
        ->select(DB::raw("lapangan2, COUNT(*) AS total_jumlah"))
        ->groupBy('lapangan2')
        ->orderBy('lapangan2');

            $kkn = DB::table(DB::raw("(SELECT 
                        CASE 
                            WHEN mk.kuliah_kerja_nyata IS NULL OR mk.kuliah_kerja_nyata = '' THEN 
                                CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END
                            ELSE CONCAT('Semester ', mk.kuliah_kerja_nyata)
                        END AS geologi
                    FROM mahasiswa AS m
                    JOIN matakuliah AS mk ON m.nim = mk.nim
                    JOIN jurusan AS j ON mk.kd_jurusan = j.kd_jurusan
                    WHERE j.jurusan = 'Teknik Pertambangan'
                ) as subquery"))
                ->select(DB::raw("kkn, COUNT(*) AS total_jumlah"))
                ->groupBy('kkn')
                ->orderBy('kkn');

    
            $seminar = DB::table('mahasiswa as m')
                ->join('matakuliah as mk', 'm.nim', '=', 'mk.nim')
                ->join('jurusan as j', 'mk.kd_jurusan', '=', 'j.kd_jurusan')
                ->where('j.jurusan', 'Teknik Pertambangan')
                ->select(DB::raw("
                    CASE 
                        WHEN mk.seminar IS NULL OR mk.seminar = '' THEN 
                            MAX(CASE WHEN mk.status = 'Lulus' THEN 'Lulus tidak terdaftar' ELSE 'Belum terdaftar' END)
                        ELSE CONCAT('Semester ', mk.seminar)
                    END AS seminar,
                    COUNT(m.nim) AS total_jumlah
                "))
                ->groupBy('seminar')
                ->orderBy('seminar');
            
    
            // Tambahkan kondisi `year` jika dipilih dan tidak sama dengan 'Semua'
            if ($year && $year !== 'Semua') {
                $kkn->whereYear('m.tahun_angkatan', '=', $year);
                $pkl->whereYear('m.tahun_angkatan', '=', $year);
                $geologi->whereYear('m.tahun_angkatan', '=', $year);
                $seminar->whereYear('m.tahun_angkatan', '=', $year);
                $lapangan1->whereYear('m.tahun_angkatan', '=', $year);
                $lapangan2->whereYear('m.tahun_angkatan', '=', $year);
            }
    
            // Tambahkan kondisi `status` jika dipilih dan tidak sama dengan 'Semua'
            if ($selectedStatus && $selectedStatus !== 'Semua') {
                $kkn->where('mk.status', '=', $selectedStatus);
                $pkl->where('mk.status', '=', $selectedStatus);
                $geologi->where('mk.status', '=', $selectedStatus);
                $seminar->where('mk.status', '=', $selectedStatus);
                $lapangan1->where('mk.status', '=', $selectedStatus);
                $lapangan2->where('mk.status', '=', $selectedStatus);
            }
    
            // Menentukan query berdasarkan pilihan mata kuliah
            if ($selectedMatakuliah === 'kkn') {
                $query = $kkn->get()->mapWithKeys(function ($item) {
                    return [$item->kkn => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'pkl') {
                $query = $pkl->get()->mapWithKeys(function ($item) {
                    return [$item->pkl => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'geologi') {
                $query = $geologi->get()->mapWithKeys(function ($item) {
                    return [$item->geologi => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'seminar') {
                $query = $seminar->get()->mapWithKeys(function ($item) {
                    return [$item->seminar => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            } elseif ($selectedMatakuliah === 'lapangan1') {
                $query = $lapangan1->get()->mapWithKeys(function ($item) {
                    return [$item->lapangan1 => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }  elseif ($selectedMatakuliah === 'lapangan2') {
                $query = $lapangan2->get()->mapWithKeys(function ($item) {
                    return [$item->lapangan2 => $item->total_jumlah];
                })->sortKeys(); // Menambahkan pengurutan di sini
            }               
            //dd($query);
            
        }
    
        // Mengembalikan view dengan data yang diperlukan
        return view('mahasiswa.jenis_matakuliah_teknik_tambang_mahasiswa', compact(
            'years', 
            'allStatus', 
            'year', 
            'selectedStatus', 
            'selectedMatakuliah', 
            'query'
        ));
    }
}
