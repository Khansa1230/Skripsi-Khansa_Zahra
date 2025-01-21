@extends('utama.kerangka.master')

@section('title', 'Dashboard')

@section('content')
<div class="page-heading">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>
                    <a href="{{ route('klasifikasi_c45_mahasiswa_jurusan_kimia') }}" style="text-decoration: none; color: inherit;">Klasifikasi Algoritma C45 Jurusan Kimia</a>
                     - Prediksi Jurusan Kimia
                </h4>
            </div>
            <form method="GET" action="{{ route('prediksi_mahasiswa_jurusan_kimia') }}" id="filter-form">
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
                        @php
                            // Menghitung nilai uji berdasarkan nilai yang dipilih
                            $predictedLulus = old('predicted_lulus', $predictedLulus);
                            $trainingValue = $predictedLulus;
                            $testingValue = 100 - $predictedLulus; // Misalkan total 100
                        @endphp

                        <label for="predicted_lulus" class="mb-0 mr-3">
                            Training {{ $trainingValue }} dan Uji {{ $testingValue }}:
                        </label>
                        <select name="predicted_lulus" id="predicted_lulus" class="form-control w-auto">
                            <option value="">Pilih Prediksi Lulus</option> <!-- Opsi default -->
                            @foreach(range(10, 100, 10) as $value) <!-- Menghasilkan opsi dari 10 hingga 100 -->
                                <option value="{{ $value }}" {{ old('predicted_lulus', $predictedLulus) == $value ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
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
                    <p>Belum bisa memberikan kesimpulan</p>
                @endif
            </div>
        </div>

        <!-- Card untuk menampilkan akurasi -->
        <div class="card mt-4">
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
                                <td>{{ isset($outputData['TP']) ? $outputData['TP'] : '-' }}</td>
                                <td>{{ isset($outputData['FN']) ? $outputData['FN'] : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Sebenarnya Tidak Lulus</td>
                                <td>{{ isset($outputData['FP']) ? $outputData['FP'] : '-' }}</td>
                                <td>{{ isset($outputData['TN']) ? $outputData['TN'] : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel 2 -->
                <div class="table-responsive">
                    <h5 class="mb-3"><strong>Hasil Model</strong></h5>
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
                                <td><strong>{{ number_format($outputData['precision'], 2) }}%</strong></td>
                                <td><strong>{{ number_format($outputData['accuracy'], 2) }}%</strong></td>
                                <td><strong>{{ number_format($outputData['recall'], 2) }}%</strong></td>
                                <td><strong>{{ number_format($outputData['f1_score'], 2) }}%</strong></td>
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
    const yearSelect = document.getElementById ('year');
    const storedYear = localStorage.getItem('selectedYear');
    if (storedYear) {
        yearSelect.value = storedYear;
    }
    yearSelect.addEventListener('change', function() {
        localStorage.setItem('selectedYear', this.value);
    });
</script>