@extends('kerangka.master')

@section('title', 'Dashboard')

@section('content')
<div class="page-heading">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Klasifikasi Algoritma C45 Jurusan Fisika</h4>
            </div>
            <form method="GET" action="{{ route('klasifikasi_c45_dan_evaluation_mahasiswa_fisika') }}" id="filter-form">
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
                    <div class="form-group">
                        <label for="predicted_lulus" class="mb-0 mr-3">Prediksi Lulus:</label>
                        <input type="number" name="predicted_lulus" id="predicted_lulus" 
                            class="form-control w-auto" min="0" max="{{ $total1 }}" 
                            value="{{ old('predicted_lulus', $predictedLulus) }}" 
                            oninput="validateNumericInput(this)">
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

        <div class="card">
            <div class="card-header">
                <h4 class="display-4 mb-0">Hasil Analisis Teks Pohon Keputusan</h4>
            </div>
            <div class="card-body">
                <pre>{{ $outputData['tree_text'] }}</pre>
            </div>
        </div>
           
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Kesimpulan</h5>
            </div>
            <div class="card-body">
                @php
                    // Debugging: Lihat isi dari outputData
                    // Ini akan menghentikan eksekusi dan menampilkan data
                    // dd($outputData);
                @endphp

                @if (!empty($outputData['first_true_path']))
                    @php
                        // Ambil kondisi pertama
                        $firstPath = $outputData['first_true_path'][0];
                        $firstCondition = $firstPath[0]; // Kategori pertama
                        $firstKolom = $columnMapping['kategori_' . explode('_', $firstCondition)[1]]; // Mendapatkan kolom
                    @endphp
                    <p>Jika {{ $firstCondition }} ({{ $firstKolom }} memenuhi syarat) dan kombinasi nilai pada atribut lain seperti:</p>
                    <ul>
                        @foreach ($outputData['first_true_path'] as $index => $path)
                            <li>
                                {{ $path[0] }} 
                                {{ $index == 0 ? 'memenuhi syarat' : ($path[1] >= 0 ? '<=' : '>') . ' ' . abs($path[1]) . ' memenuhi syarat' }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>Tidak ada jalur yang mengarah ke klasifikasi True.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <strong>Data Prediksi Kelulusan Mahasiswa {{ isset($predictedLulus) ? $predictedLulus : 0 }} - Tahun Angkatan {{ $year }}</strong>
            </div>
            <div class="card-body">
                <!-- Tabel 1 -->
                <div class="table-responsive mb-5">
                    <h5 class="mb-3"><strong>Confusion Matrix</strong></h5>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>Prediksi Lulus</th>
                                <th>Prediksi Tidak Lulus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sebenarnya Lulus</td>
                                <td>{{ isset($evaluation['TP']) ? $evaluation['TP'] : '-' }}</td>
                                <td>{{ isset($evaluation['FN']) ? $evaluation['FN'] : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Sebenarnya Tidak Lulus</td>
                                <td>{{ isset($evaluation['FP']) ? $evaluation['FP'] : '-' }}</td>
                                <td>{{ isset($evaluation['TN']) ? $evaluation['TN'] : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel 2 -->
                <div class="table-responsive">
                    <h5 class="mb-3"><strong>Evaluation Metrics</strong></h5>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Precision</th>
                                <th>Accuracy</th>
                                <th>Recall</th>
                                <th>F1 Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ isset($evaluation['precision']) ? number_format($evaluation['precision'] * 100, 2) . '%' : '-' }}</td>
                                <td>{{ isset($evaluation['accuracy']) ? number_format($evaluation['accuracy'] * 100, 2) . '%' : '-' }}</td>
                                <td>{{ isset($evaluation['recall']) ? number_format($evaluation['recall'] * 100, 2) . '%' : '-' }}</td>
                                <td>{{ isset($evaluation['f1_score']) ? number_format($evaluation['f1_score'] * 100, 2) . '%' : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
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