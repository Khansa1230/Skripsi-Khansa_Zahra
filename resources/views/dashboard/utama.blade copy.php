@extends('kerangka.master')
@section('title', 'Dashboard')
@section('content')
<div class="page-heading">
    <section class="section">
        <div class="card">
            <form method="GET" action="{{ route('utama') }}" id="filter-form">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="year">Tahun Angkatan:</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">Pilih Tahun</option>
                            <option value="Semua" {{ request('year') == 'Semua' ? 'selected' : '' }}>Semua</option>
                            @foreach($years as $yearOption)
                            <option value="{{ $yearOption->year }}" {{ request('year') == $yearOption->year ? 'selected' : '' }}>
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
                @if (!empty($result1)) <!-- Use $result1 instead of $result -->
                <div class="card-header">
                    Data Status Mahasiswa - Tahun Angkatan {{ $year }}
                </div>
                <div class="table-responsive">
                    <table class="table" id="table2">
                        <thead>
                            <tr>
                                <th></th> <!-- Empty column header -->
                                <th>Jumlah Mahasiswa</th>
                                <th>Belum Lulus</th>
                                <th>Lulus</th>
                                <th>Entropy</th>
                                <th>Gain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Calculate totals for each status type
                                $totalMahasiswa = 0;
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop through results to calculate the sums
                                foreach ($result1 as $item) {  // Changed from $result to $result1
                                    $totalMahasiswa += $item->total_mahasiswa;
                                    if ($item->jenis_status == 'Belum Lulus') {
                                        $totalBelumLulus += $item->total_mahasiswa;
                                    } elseif ($item->jenis_status == 'Lulus') {
                                        $totalLulus += $item->total_mahasiswa;
                                    } 
                                }
                            @endphp
                            <tr>
                                <td></td> <!-- Empty column cell -->
                                <td><strong>{{ $totalMahasiswa }}</strong></td>
                                <td><strong>{{ $totalBelumLulus }}</strong></td>
                                <td><strong>{{ $totalLulus }}</strong></td>
                                <td><strong>{{ round($entropyTotal1, 4) }}</strong></td> <!-- Use $entropyTotal1 -->
                                <td></td>
                            </tr>
                            
                            <tr>
                                <td class="fw-bold fs-5">Jenis Status</td>
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
                                foreach ($total2 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total2 as $kategori => $item)
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
                                <td class="fw-bold fs-5">Jenis Tahun Lulus</td>
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
                                foreach ($total4 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            @endphp

                            <!-- Menampilkan data berdasarkan status -->
                            @foreach ($total4 as $kategori => $item)
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
                            
                        </tbody>
                    </table>
                </div>
                <div>
    @if (isset($prediction1) && $prediction1 !== null) <!-- Check if prediction1 is set and not null -->
    <p><strong>Prediksi Status untuk 30 Mahasiswa (Prediksi 1):</strong> {{ $prediction1 }}</p>
    @elseif (isset($prediction2) && $prediction2 !== null) <!-- Check if prediction2 is set and not null -->
    <p><strong>Prediksi Status untuk 30 Mahasiswa (Prediksi 2):</strong> {{ $prediction2 }}</p>
    @else
    <p>Prediksi tidak tersedia untuk data ini.</p>
    @endif
</div>

                @else
                <p>Tidak ada data yang tersedia untuk tahun angkatan yang dipilih.</p>
                @endif
            </div>
        </div>
        <div class="card">
    <div class="card-header">
        Data Prediksi Kelulusan Mahasiswa - Tahun Angkatan {{ $year }}
    </div>
    <div class="card-body">
        <div class="form-group d-flex align-items-center">
            <label for="predicted_lulus" class="mb-0 mr-3">Prediksi Lulus:</label>
            <input type="number" name="predicted_lulus" id="predicted_lulus" 
                class="form-control w-auto" min="0" max="{{ $totalMahasiswa }}" 
                value="{{ request('predicted_lulus') ?? '' }}" 
                oninput="validateNumericInput(this)" >
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Precision</th>
                        <th>Accuracy</th>
                        <th>Recall</th>
                        <th>F1 Score</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Menambahkan baris untuk data evaluasi -->
                    <tr>
                        <td></td>
                        <td>{{ $evaluation['accuracy'] ?? '-' }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

    </section>
</div>
@endsection
