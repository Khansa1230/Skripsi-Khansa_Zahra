@extends('fisika.kerangka.master')
@section('title', 'Dashboard')
@section('content')
<div class="container">
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Jumlah Mahasiswa Berdasarkan kota</h4>
                            </div>
                            <form method="GET" action="{{ route('jumlah_mahasiswa_kota_fisika') }}" id="filter-form">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="year">Tahun Angkatan:</label>
                                        <select name="year" id="year" class="form-control" onchange="updatePropinsi()">
                                            <option value="">Pilih Tahun</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year->year }}" {{ request('year') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" id="status" class="form-control" onchange="updatePropinsi()">
                                            <option value="">Semua</option>
                                            @foreach($allStatus as $status)
                                                <option value="{{ $status->status }}" {{ $selectedStatus == $status->status ? 'selected' : '' }}>{{ $status->status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                                                        <div class="form-group">
                                        <label for="propinsi">Propinsi:</label>
                                        <select name="propinsi" id="propinsi" class="form-control">
                                            <option value="Semua">Semua</option>
                                            @foreach ($allPropinsi as $propinsi)
                                                <option value="{{ $propinsi->propinsi }}">{{ $propinsi->propinsi }}</option>
                                                @php echo $propinsi->propinsi; @endphp <!-- Debug: print setiap value di sini -->
                                            @endforeach
                                        </select>
                                    </div>
                                    <ul>
                                        @foreach ($allPropinsi as $propinsi)
                                            <li>{{ $propinsi->propinsi }}</li>
                                        @endforeach
                                    </ul>


                                </div>
                            </form>
                        </div>
                    </div>  
                </div>
            </div>
        </section>
    </div>
</div>
<style>
    .chartMenu {
        width: 100%;
        height: 40px;
    }

    .theme-dark.chartCard {
        width: 100%;
        height: calc(90vh - 30px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chartBox {
        width: 100%;
        padding: 20px;
        border-radius: 20px;
        border: solid 3px rgba(0, 95, 153, 0.72);
        background: white;
        display: flex;
        flex-direction: column;
        height: 80%;
    }

    .box {
        width: 100%;
        height: 800px;
        flex: 1;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.1/dist/chartjs-plugin-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
function updatePropinsi() {
    const year = document.getElementById('year').value;
    const status = document.getElementById('status').value;
    const jurusan = document.getElementById('jurusan').value;

    // Cek apakah ada parameter yang dipilih
    if (!year && !status && !jurusan) {
        // Jika tidak ada pilihan, kosongkan dropdown propinsi
        document.getElementById('propinsi').innerHTML = '<option value="">Pilih Propinsi</option>';
        return; // Tidak perlu melanjutkan
    }

    // Menggunakan fetch untuk mengambil data propinsi
    fetch(`/get-propinsi?year=${year}&status=${status}&jurusan=${jurusan}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const propinsiSelect = document.getElementById('propinsi');
            propinsiSelect.innerHTML = ''; // Kosongkan opsi yang ada

            // Menambahkan opsi default
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Pilih Propinsi';
            propinsiSelect.add(defaultOption);

            // Cek jika ada data propinsi
            if (data.propinsi.length > 0) {
                // Tambahkan opsi baru berdasarkan data yang diterima
                data.propinsi.forEach(pro => {
                    if (pro.propinsi) { // Pastikan propinsi tidak kosong
                        const option = document.createElement('option');
                        option.value = pro.propinsi;
                        option.text = pro.propinsi;
                        propinsiSelect.add(option);
                    }
                });
            } else {
                console.log("Tidak ada propinsi yang ditemukan.");
            }
        })
        .catch(error => {
            console.error('Error fetching propinsi:', error);
            // Mengosongkan dropdown propinsi jika terjadi kesalahan
            const propinsiSelect = document.getElementById('propinsi');
            propinsiSelect.innerHTML = '<option value="">Pilih Propinsi</option>';
        });
}
</script>




@endsection
