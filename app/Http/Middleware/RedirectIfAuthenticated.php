<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         return redirect(RouteServiceProvider::'/dashboard');
        //     }
        // }
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->route('dashboard'); // Pastikan 'dashboard' adalah nama rute yang sesuai
            }
        }
        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         // Mendapatkan pengguna yang sedang login
        //         $user = Auth::user(); 
                
        //         // Logika untuk memilih rute berdasarkan level pengguna
        //         if ($user->level == 1) {
        //             return redirect()->route('dashboard'); // Arahkan ke dashboard untuk admin
        //         } elseif ($user->level == 2) {
        //             return redirect()->route('utama'); // Arahkan ke halaman utama untuk pengguna biasa
        //         } elseif ($user->level == 3) {
        //             return redirect()->route('jumlah_mahasiswa_kelamin'); // Arahkan ke rute ini untuk level 3
        //         } elseif ($user->level == 4) {
        //             return redirect()->route('jumlah_mahasiswa_status'); // Arahkan ke rute ini untuk level 4
        //         } elseif ($user->level == 5) {
        //             return redirect()->route('jumlah_mahasiswa_jenis_seleksi'); // Arahkan ke rute untuk level lainnya
        //         } elseif ($user->level == 6){
        //             return redirect()->route('jumlah_mahasiswa_propinsi');
        //         } elseif ($user->level==7) {
        //             return redirect()->route('jumlah_mahasiswa_kota');
        //         } elseif ($user->level==8){
        //             return redirect()->route('jumlah_mahasiswa_jenis_sekolah');
        //         } elseif ($user->level==9){
        //             return redirect()->route('jumlah_mahasiswa_satuan_kredit_semester');
        //         }  elseif ($user->level==10){
        //             return redirect()->route('jumlah_mahasiswa_indeks_prestasi_kumulatif');
        //         } elseif ($user->level==11){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_teknik_informatika');
        //         } elseif ($user->level==12){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_agribisnis');
        //         } elseif ($user->level==13){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_biologi');
        //         } elseif ($user->level==12){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_fisika');
        //         } elseif ($user->level==14){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_kimia');
        //         } elseif ($user->level==15){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_matematika');
        //         }  elseif ($user->level==16){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_sistem_informasi');
        //         } elseif ($user->level==17){
        //             return redirect()->route('jumlah_mahasiswa_matakuliah_teknik_tambang');
        //         } elseif ($user->level==18){
        //             return redirect()->route('matakuliah');
        //         } elseif ($user->level==19){
        //             return redirect()->route('algoritma');
        //         } elseif ($user->level==20){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_agribisnis');
        //         } elseif ($user->level==21){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_biologii');
        //         } elseif ($user->level==22){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_fisika');
        //         } elseif ($user->level==23){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_kimia');
        //         } elseif ($user->level==24){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_matematika');
        //         } elseif ($user->level==25){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_sistem_informasi');
        //         } elseif ($user->level==26){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_teknik_informatika');
        //         } elseif ($user->level==27){
        //             return redirect()->route('klasifikasi_c45_dan_evaluation_mahasiswa_teknik_tambang');
        //         }
        //     }
        // }
        
        // Jika tidak ada pengguna yang terautentikasi, lanjutkan dengan request
        return $next($request);
    }        
}