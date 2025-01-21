@extends('utama.kerangka.master')
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
                                <h4>Jumlah Mahasiswa Berdasarkan Jenis Jurusan</h4>
                            </div>
                            <form method="GET" action="{{ route('jumlah_mahasiswa_jurusan') }}" id="filter-form">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="year">Tahun Angkatan:</label>
                                        <select name="year" id="year" class="form-control">
                                            <option value="">Pilih Tahun</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year->year }}" {{ request('year') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Semua</option> <!-- Menambahkan opsi "Semua" -->
                                            @foreach($allStatus as $status)
                                                <option value="{{ $status->status }}" {{ $selectedStatus == $status->status ? 'selected' : '' }}>{{ $status->status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="card-body">
                                    <div class="chartCard">
                                        <div class="chartBox">
                                            <div class="box">
                                            @if(count($query) > 0)
                                                <canvas id="BarChartSum2" width="600" height="400"></canvas>
                                            @else
                                                <p>No data available for the selected jurusan.</p>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
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
    var query = @json($query);

    const labels = query.map(item => item.jurusan); // Mengambil status dari query
    const data = query.map(item => item.jumlah_mahasiswa); // Mengambil jumlah mahasiswa dari query
    const ctx2 = document.getElementById('BarChartSum2').getContext('2d');
    const backgroundColor = data.map(() => 
        `rgba(${Math.floor(Math.random() * 156) + 100}, ${Math.floor(Math.random() * 156) + 100},
        ${Math.floor(Math.random() * 156) + 100}, 0.8)` // Warna acak yang lebih cerah dengan transparansi 0.8
    );

    const myChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Jumlah Mahasiswa",
                data: data,
                backgroundColor: backgroundColor, // Menggunakan warna latar belakang yang sudah dibuat
                borderColor: backgroundColor, // Menggunakan warna latar belakang sebagai warna border
                borderWidth: 4 // Ketebalan border yang telah diubah menjadi 0.9
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    anchor: 'end', // Anchor untuk label di ujung
                    align: 'start', // Menempatkan label di atas batang
                    formatter: (value) => value, // Menampilkan nilai di atas batang
                    font: {
                        size: 16, // Mengatur ukuran font menjadi lebih besar
                        weight: 'bold' // Mengatur ketebalan font menjadi tebal
                    },
                    color: 'black', // Mengatur warna label
                    offset: 5, // Offset untuk menggeser label sedikit lebih tinggi
                },
            },
            scales: {
                x: {
                    min: 0,
                    max: 4, // Menampilkan hanya 5 batang
                    ticks: {
                        font: {
                            size: 14, // Mengatur ukuran font sumbu x
                            weight: 'bold' // Mengatur ketebalan font sumbu x
                        },
                        callback: function(value) {
                            const maxLength = 15; // Maksimal karakter sebelum pecah baris
                            const label = this.getLabelForValue(value);
                            if (label.length > maxLength) {
                                let words = label.split(' ');
                                let lines = [];
                                let line = '';

                                words.forEach(word => {
                                    if ((line + word).length > maxLength) {
                                        lines.push(line);
                                        line = word + ' ';
                                    } else {
                                        line += word + ' ';
                                    }
                                });
                                lines.push(line.trim());
                                return lines;
                            } else {
                                return label;
                            }
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 14, // Mengatur ukuran font sumbu y
                            weight: 'bold' // Mengatur ketebalan font sumbu y
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 14, // Mengatur ukuran font label legenda
                            weight: 'bold' // Mengatur ketebalan font label legenda
                        }
                    }
                }
            }
        },
        plugins: [ChartDataLabels], // Menambahkan plugin ChartDataLabels
    });

    function scroller(event, chart) {
        const dataLength = chart.data.labels.length;
        if (event.deltaY > 0) {
            if (chart.options.scales.x.max < dataLength - 1) {
                chart.options.scales.x.min += 1;
                chart.options.scales.x.max += 1;
            }
        } else if (event.deltaY < 0) {
            if (chart.options.scales.x.min > 0) {
                chart.options.scales.x.min -= 1;
                chart.options.scales.x.max -= 1;
            }
        }
        chart.update(); // Memanggil metode update untuk memperbarui chart
    }

    ctx2.canvas.addEventListener('wheel', (e) => {
        scroller(e, myChart);
        e.preventDefault(); // Mencegah default scroll behavior
    });
</script>

@endsection
