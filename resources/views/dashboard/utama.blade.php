@extends('kerangka.master')

@section('title', 'Dashboard')

@section('content')
<div class="page-heading">
    <section class="section">
        <div class="container">

            <!-- Dropdown Tahun Angkatan -->
            <div class="form-group">
                <label for="year">Tahun Angkatan:</label>
                <form action="{{ route('utama') }}" method="GET"> <!-- Ganti 'nama.route.anda' dengan route yang sesuai -->
                    <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                        <option value="Semua" {{ $year == 'Semua' ? 'selected' : '' }}>Semua</option>
                        @foreach($years as $yearOption)
                        <option value="{{ $yearOption->year }}" {{ $year == $yearOption->year ? 'selected' : '' }}>
                            {{ $yearOption->year }}
                        </option>
                        @endforeach
                    </select>
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
                <div class="card-header">
                    <h5 class="mb-0">Kesimpulan</h5>
                </div>
                <div class="card-body">
                    @if (!empty($outputData['first_true_path']))
                        @php
                            $firstPath = $outputData['first_true_path'][0][0];
                            $kategori = explode('_', $firstPath)[1];
                            $kolom = $columnMapping['kategori_' . $kategori];
                        @endphp
                        <p>Jika {{ $firstPath }} ({{ $kolom }} memenuhi syarat) dan kombinasi nilai pada atribut lain seperti:</p>
                        <ul>
                            @foreach ($outputData['first_true_path'] as $index => $path)
                                <li>
                                    {{ $path[0] }} 
                                    @if ($index == 0)
                                        memenuhi syarat
                                    @else
                                        {{ $path[1] >= 0 ? '<=' : '>' }} {{ abs($path[1]) }} memenuhi syarat
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada jalur yang mengarah ke klasifikasi True.</p>
                    @endif
                </div>
            </div>

        </div>
    </section>
</div>
@endsection