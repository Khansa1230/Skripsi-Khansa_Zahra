<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtamaController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AlgoritmaController;
use App\Http\Controllers\TeknikInformatikaController;
use App\Http\Controllers\KlasifikasiC45TeknikInformatikaController;
use App\Http\Controllers\KlasifikasiC45SistemInformasiController;
use App\Http\Controllers\KlasifikasiC45AgribisnisController;
use App\Http\Controllers\KlasifikasiC45FisikaController;
use App\Http\Controllers\KlasifikasiC45MatematikaController;
use App\Http\Controllers\KlasifikasiC45KimiaController;
use App\Http\Controllers\KlasifikasiC45BiologiController;
use App\Http\Controllers\KlasifikasiC45TeknikTambangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('kerangka.master');
// });

// Route::get('/dashboard',[DashboardController::class,'index'])->middleware('auth');
// Route::get('/utama',[UtamaController::class,'index']);

// Route::middleware(['auth', 'shareMahasiswaData'])->group(function () {
//     // Rute yang membutuhkan autentikasi dan share data mahasiswa
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::get('/utama', [UtamaController::class, 'index'])->name('utama');
//     // Tambahkan rute lainnya di sini
// });



// Route::get('/',[LoginController::class,'index'])->name('login')-> middleware('guest');
// Route::post('/log',[LoginController::class,'login'])->name('login.store');

// Route::get('/register',[RegisterController::class,'register'])->name('register');
// Route::post('/regist',[RegisterController::class,'store'])->name('register.store');

// Rute yang membutuhkan autentikasi dan berbagi data mahasiswa
  Route::middleware(['auth', 'shareMahasiswaData'])->group(function () {
   
   
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/utama', [UtamaController::class, 'index'])->name('utama');
    Route::get('/matakuliah', [MatakuliahController::class, 'index'])->name('matakuliah');
    Route::get('/algoritma', [AlgoritmaController::class, 'index'])->name('algoritma');


    
    // Rute Mahasiswa dengan nama yang lebih konsisten
    Route::get('/jumlah_mahasiswa_kelamin', [MahasiswaController::class, 'jumlah_mahasiswa_kelamin'])->name('jumlah_mahasiswa_kelamin');
    Route::get('/jumlah_mahasiswa_status', [MahasiswaController::class, 'jumlah_mahasiswa_status'])->name('jumlah_mahasiswa_status');
    Route::get('/jumlah_mahasiswa_jurusan', [MahasiswaController::class, 'jumlah_mahasiswa_jurusan'])->name('jumlah_mahasiswa_jurusan');
    Route::get('/jumlah_mahasiswa_jenis_seleksi', [MahasiswaController::class, 'jumlah_mahasiswa_jenis_seleksi'])->name('jumlah_mahasiswa_jenis_seleksi');
    Route::get('/jumlah_mahasiswa_propinsi', [MahasiswaController::class, 'jumlah_mahasiswa_propinsi'])->name('jumlah_mahasiswa_propinsi');
    Route::get('/jumlah_mahasiswa_kota', [MahasiswaController::class, 'jumlah_mahasiswa_kota'])->name('jumlah_mahasiswa_kota');
    Route::get('/jumlah_mahasiswa_jenis_sekolah', [MahasiswaController::class, 'jumlah_mahasiswa_jenis_sekolah'])->name('jumlah_mahasiswa_jenis_sekolah');
    Route::get('/jumlah_mahasiswa_satuan_kredit_semester', [MahasiswaController::class, 'jumlah_mahasiswa_satuan_kredit_semester'])->name('jumlah_mahasiswa_satuan_kredit_semester');
    Route::get('/jumlah_mahasiswa_indeks_prestasi_kumulatif', [MahasiswaController::class, 'jumlah_mahasiswa_indeks_prestasi_kumulatif'])->name('jumlah_mahasiswa_indeks_prestasi_kumulatif');
    Route::get('/jumlah_mahasiswa_matakuliah_teknik_informatika', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_teknik_informatika'])->name('jumlah_mahasiswa_matakuliah_teknik_informatika');
    Route::get('/jumlah_mahasiswa_matakuliah_agribisnis', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_agribisnis'])->name('jumlah_mahasiswa_matakuliah_agribisnis');
    Route::get('/jumlah_mahasiswa_matakuliah_biologi', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_biologi'])->name('jumlah_mahasiswa_matakuliah_biologi');
    Route::get('/jumlah_mahasiswa_matakuliah_fisika', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_fisika'])->name('jumlah_mahasiswa_matakuliah_fisika');
    Route::get('/jumlah_mahasiswa_matakuliah_kimia', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_kimia'])->name('jumlah_mahasiswa_matakuliah_kimia');
    Route::get('/jumlah_mahasiswa_matakuliah_matematika', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_matematika'])->name('jumlah_mahasiswa_matakuliah_matematika');
    Route::get('/jumlah_mahasiswa_matakuliah_sistem_informasi', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_sistem_informasi'])->name('jumlah_mahasiswa_matakuliah_sistem_informasi');
    Route::get('/jumlah_mahasiswa_matakuliah_teknik_tambang', [MatakuliahController::class, 'jumlah_mahasiswa_matakuliah_teknik_tambang'])->name('jumlah_mahasiswa_matakuliah_teknik_tambang');
    Route::get('/klasifikasi_c45_mahasiswa_teknik_informatika', [KlasifikasiC45TeknikInformatikaController::class, 'klasifikasi_mahasiswa_teknik_informatika'])->name('klasifikasi_c45_mahasiswa_teknik_informatika');
    Route::get('/prediksi_mahasiswa_teknik_informatika', [KlasifikasiC45TeknikInformatikaController::class, 'prediksi_mahasiswa_teknik_informatika'])->name('prediksi_mahasiswa_teknik_informatika');
    Route::get('/klasifikasi_c45_mahasiswa_sistem_informasi', [KlasifikasiC45SistemInformasiController::class, 'klasifikasi_mahasiswa_sistem_informasi'])->name('klasifikasi_c45_mahasiswa_sistem_informasi');
    Route::get('/prediksi_mahasiswa_sistem_informasi', [KlasifikasiC45SistemInformasiController::class, 'prediksi_mahasiswa_sistem_informasi'])->name('prediksi_mahasiswa_sistem_informasi');
    Route::get('/klasifikasi_c45_mahasiswa_agribisnis', [KlasifikasiC45AgribisnisController::class, 'klasifikasi_mahasiswa_agribisnis'])->name('klasifikasi_c45_mahasiswa_agribisnis');
    Route::get('/prediksi_mahasiswa_agribisnis', [KlasifikasiC45AgribisnisController::class, 'prediksi_mahasiswa_agribisnis'])->name('prediksi_mahasiswa_agribisnis');
    Route::get('/klasifikasi_c45_mahasiswa_fisika', [KlasifikasiC45FisikaController::class, 'klasifikasi_mahasiswa_fisika'])->name('klasifikasi_c45_mahasiswa_fisika');
    Route::get('/prediksi_mahasiswa_fisika', [KlasifikasiC45FisikaController::class, 'prediksi_mahasiswa_fisika'])->name('prediksi_mahasiswa_fisika');
    Route::get('/klasifikasi_c45_mahasiswa_matematika', [KlasifikasiC45MatematikaController::class, 'klasifikasi_mahasiswa_matematika'])->name('klasifikasi_c45_mahasiswa_matematika');
    Route::get('/prediksi_mahasiswa_matematika', [KlasifikasiC45MatematikaController::class, 'prediksi_mahasiswa_matematika'])->name('prediksi_mahasiswa_matematika');
    Route::get('/klasifikasi_c45_mahasiswa_kimia', [KlasifikasiC45KimiaController::class, 'klasifikasi_mahasiswa_kimia'])->name('klasifikasi_c45_mahasiswa_kimia');
    Route::get('/prediksi_mahasiswa_kimia', [KlasifikasiC45KimiaController::class, 'prediksi_mahasiswa_kimia'])->name('prediksi_mahasiswa_kimia');
    Route::get('/klasifikasi_c45_mahasiswa_biologi', [KlasifikasiC45BiologiController::class, 'klasifikasi_mahasiswa_biologi'])->name('klasifikasi_c45_mahasiswa_biologi');
    Route::get('/prediksi_mahasiswa_biologi', [KlasifikasiC45BiologiController::class, 'prediksi_mahasiswa_biologi'])->name('prediksi_mahasiswa_biologi');
    Route::get('/klasifikasi_c45_mahasiswa_teknik_tambang', [KlasifikasiC45TeknikTambangController::class, 'klasifikasi_mahasiswa_teknik_tambang'])->name('klasifikasi_c45_mahasiswa_teknik_tambang');
    Route::get('/prediksi_mahasiswa_teknik_tambang', [KlasifikasiC45TeknikTambangController::class, 'prediksi_mahasiswa_teknik_tambang'])->name('prediksi_mahasiswa_teknik_tambang');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Tambahkan rute lainnya di sini yang memerlukan autentikasi
});

// Rute untuk pengguna yang belum login (guest)
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/log', [LoginController::class, 'login'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/regist', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/forgot', [ForgotController::class, 'index'])->name('forgot');
    Route::post('/forg', [ForgotController::class, 'store'])->name('forgot.store');

});

// Rute logout yang bisa diakses oleh pengguna yang sudah login
//Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
