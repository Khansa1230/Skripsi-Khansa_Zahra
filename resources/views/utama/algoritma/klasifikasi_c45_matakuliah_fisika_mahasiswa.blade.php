@extends('utama.kerangka.master')

@section('title', 'Dashboard')

@section('content')
<div class="page-heading">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>
                    Klasifikasi Algoritma C45 Jurusan Fisika - 
                    <a href="{{ route('prediksi_mahasiswa_jurusan_fisika') }}" style="text-decoration: none; color: inherit;">Prediksi Jurusan Fisika</a>
                </h4>
            </div>
            <form method="GET" action="{{ route('klasifikasi_c45_mahasiswa_jurusan_fisika') }}" id="filter-form">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="year">Tahun Angkatan:</label>
                        <select name="year" id="year" class="form-control">
                            <option value="Semua" {{ $year == 'Semua' ? 'selected' : '' }}>Semua</option>
                            @foreach($years as $yearOption)
                            <option value="{{ $yearOption->year }}" {{ $year == $yearOption->year ? 'selected' : '' }}>
                                {{ $yearOption->year }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    

                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    Data Status Mahasiswa - Tahun Angkatan {{ $year }}
                </div>
                    <div class="table-responsive">
                        <table class="table" id="table2">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Jumlah Mahasiswa</th>
                                    <th>Belum Lulus</th>
                                    <th>Lulus</th>
                                    <th>Entropy</th>
                                    <th>Gain</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalMahasiswa = 0;
                                    $totalBelumLulus = 0;
                                    $totalLulus = 0;
                                @endphp
                                @foreach ($result1 as $item)
                                    @php
                                        $totalMahasiswa += $item->total_mahasiswa;
                                        if ($item->jenis_status == 'Belum Lulus') {
                                            $totalBelumLulus += $item->total_mahasiswa;
                                        } elseif ($item->jenis_status == 'Lulus') {
                                            $totalLulus += $item->total_mahasiswa;
                                        }
                                    @endphp
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td><strong>{{ $totalMahasiswa }}</strong></td>
                                    <td><strong>{{ $totalBelumLulus }}</strong></td>
                                    <td><strong>{{ $totalLulus }}</strong></td>
                                    <td><strong>{{ round($entropyTotal1, 4) }}</strong></td>
                                    <td></td>
                                </tr>

                                
                                
                                <tr>
                                    <td class="fw-bold fs-5">Jenis Sekolah Mahasiswa</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            @php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total3 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total3 as $kategori => $item)
                                <tr>
                                    <td>{{ $kategori }}</td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong>{{ $item['total_mahasiswa'] }}</strong></td>
                                    <td><strong>{{ $item['belum_lulus'] }}</strong></td>
                                    <td><strong>{{ $item['lulus'] }}</strong></td>
                                    <td><strong>{{ round($item['entropy'], 4) }}</strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    @if ($loop->first) <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong>{{ round($item['gain_45'], 4) }}</strong></td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach

                                
                            <tr>
                                <td class="fw-bold fs-5">Satuan Kredit Semester</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total5 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total5 as $kategori => $item)
                                <tr>
                                    <td>{{ $kategori }}</td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong>{{ $item['total_mahasiswa'] }}</strong></td>
                                    <td><strong>{{ $item['belum_lulus'] }}</strong></td>
                                    <td><strong>{{ $item['lulus'] }}</strong></td>
                                    <td><strong>{{ round($item['entropy'], 4) }}</strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    @if ($loop->first) <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong>{{ round($item['gain_45'], 4) }}</strong></td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach

                            <tr>
                                <td class="fw-bold fs-5">Jenis Indeks Prestasi Kumulatif</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total6 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total6 as $kategori => $item)
                                <tr>
                                    <td>{{ $kategori }}</td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong>{{ $item['total_mahasiswa'] }}</strong></td>
                                    <td><strong>{{ $item['belum_lulus'] }}</strong></td>
                                    <td><strong>{{ $item['lulus'] }}</strong></td>
                                    <td><strong>{{ round($item['entropy'], 4) }}</strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    @if ($loop->first) <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong>{{ round($item['gain_45'], 4) }}</strong></td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach

                            <tr>
                                <td class="fw-bold fs-5">Jenis Praktek Kerja Lapangan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total7 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total7 as $kategori => $item)
                                <tr>
                                    <td>{{ $kategori }}</td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong>{{ $item['total_mahasiswa'] }}</strong></td>
                                    <td><strong>{{ $item['belum_lulus'] }}</strong></td>
                                    <td><strong>{{ $item['lulus'] }}</strong></td>
                                    <td><strong>{{ round($item['entropy'], 4) }}</strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    @if ($loop->first) <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong>{{ round($item['gain_45'], 4) }}</strong></td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach

                            <tr>
                                <td class="fw-bold fs-5">Jenis Kuliah Kerja Nyata</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total8 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total8 as $kategori => $item)
                                <tr>
                                    <td>{{ $kategori }}</td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong>{{ $item['total_mahasiswa'] }}</strong></td>
                                    <td><strong>{{ $item['belum_lulus'] }}</strong></td>
                                    <td><strong>{{ $item['lulus'] }}</strong></td>
                                    <td><strong>{{ round($item['entropy'], 4) }}</strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    @if ($loop->first) <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong>{{ round($item['gain_45'], 4) }}</strong></td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach

                            <tr>
                                <td class="fw-bold fs-5">Jenis Seminar</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total9 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total9 as $kategori => $item)
                                <tr>
                                    <td>{{ $kategori }}</td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong>{{ $item['total_mahasiswa'] }}</strong></td>
                                    <td><strong>{{ $item['belum_lulus'] }}</strong></td>
                                    <td><strong>{{ $item['lulus'] }}</strong></td>
                                    <td><strong>{{ round($item['entropy'], 4) }}</strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    @if ($loop->first) <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong>{{ round($item['gain_45'], 4) }}</strong></td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach

                                
                                
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

       
    </section>
</div>
@endsection

<script>
    // Simpan nilai filter saat halaman dimuat
    const yearSelect = document.getElementById('year');
    const storedYear = localStorage.getItem('selectedYear');
    if (storedYear) {
        yearSelect.value = storedYear;
    }
    yearSelect.addEventListener('change', function() {
        localStorage.setItem('selectedYear', this.value);
    });
</script>