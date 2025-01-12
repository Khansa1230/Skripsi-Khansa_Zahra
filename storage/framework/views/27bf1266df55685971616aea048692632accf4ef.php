
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Jumlah Mahasiswa Berdasarkan Jenis Matakuliah Matematika</h4>
                            </div>
                            <form method="GET" action="<?php echo e(route('jumlah_mahasiswa_matakuliah_matematika')); ?>" id="filter-form">
                                <?php echo csrf_field(); ?>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="year">Tahun Angkatan:</label>
                                        <select name="year" id="year" class="form-control">
                                            <option value="">Semua</option>
                                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($year->year); ?>" <?php echo e(request('year') == $year->year ? 'selected' : ''); ?>><?php echo e($year->year); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Semua</option> <!-- Menambahkan opsi "Semua" -->
                                            <?php $__currentLoopData = $allStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($status->status); ?>" <?php echo e($selectedStatus == $status->status ? 'selected' : ''); ?>><?php echo e($status->status); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="matakuliah">Jurusan:</label>
                                        <select name="matakuliah" id="matakuliah" name="matakuliah" class="form-control">
                                            <option name="kkn" value="kkn">Kuliah Kerja Nyata</option>
                                            <option name="pkl" value="pkl">Praktek Kerja Lapangan</option>
                                            <option name="seminar" value="seminar">Seminar</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="card-body">
                                    <div class="chartCard">
                                        <div class="chartBox">
                                            <div class="box">
                                            <?php if(count($query) > 0): ?>
                                                <canvas id="BarChartSum2" width="600" height="400"></canvas>
                                            <?php else: ?>
                                                <p>No data available for the selected status and jurusan.</p>
                                            <?php endif; ?>
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
    // Simpan nilai-nilai filter saat halaman dimuat
    var yearSelect = document.getElementById('year');
    var statusSelect = document.getElementById('status');
    var matakuliahSelect = document.getElementById('matakuliah');

    // Mengecek apakah ada nilai yang tersimpan di local storage
    var storedYear = localStorage.getItem('selectedYear');
    var storedStatus = localStorage.getItem('selectedStatus');
    var storedMatakuliah = localStorage.getItem('selectedMatakuliah');

    // Jika ada nilai yang tersimpan, set nilai-nilai filter sesuai dengan nilai yang tersimpan
    if (storedYear) {
        yearSelect.value = storedYear;
    }

    if (storedStatus) {
        statusSelect.value = storedStatus; // Menggunakan statusSelect yang benar
    }

    if (storedMatakuliah) {
        matakuliahSelect.value = storedMatakuliah; // Menggunakan matakuliahSelect yang benar
    }

    // Menyimpan nilai-nilai filter saat berubah
    yearSelect.addEventListener('change', function() {
        localStorage.setItem('selectedYear', yearSelect.value);
    });

    statusSelect.addEventListener('change', function() {
        localStorage.setItem('selectedStatus', statusSelect.value);
    });

    matakuliahSelect.addEventListener('change', function() {
        localStorage.setItem('selectedMatakuliah', matakuliahSelect.value); // Menggunakan matakuliahSelect yang benar
    });
</script>

<script>
    var query = <?php echo json_encode($query, 15, 512) ?>;
</script>

<script>
    var labels = Object.keys(query);
    var data = Object.values(query);
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('kerangka.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\skripsi\resources\views/mahasiswa/jenis_matakuliah_matematika_mahasiswa.blade.php ENDPATH**/ ?>