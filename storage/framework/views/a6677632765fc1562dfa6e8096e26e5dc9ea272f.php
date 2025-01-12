

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Klasifikasi Algoritma C45 Jurusan Teknik Informatika</h4>
            </div>
            <form method="GET" action="<?php echo e(route('klasifikasi_c45_dan_evaluation_mahasiswa_teknik_informatika')); ?>" id="filter-form">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="year">Tahun Angkatan:</label>
                        <select name="year" id="year" class="form-control">
                            <option value="Semua" <?php echo e($year == 'Semua' ? 'selected' : ''); ?>>Semua</option>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($yearOption->year); ?>" <?php echo e($year == $yearOption->year ? 'selected' : ''); ?>>
                                <?php echo e($yearOption->year); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="predicted_lulus" class="mb-0 mr-3">Prediksi Lulus:</label>
                        <input type="number" name="predicted_lulus" id="predicted_lulus" 
                            class="form-control w-auto" min="0" max="<?php echo e($total1); ?>" 
                            value="<?php echo e(old('predicted_lulus', $predictedLulus)); ?>" 
                            oninput="validateNumericInput(this)">
                    </div>
                    

                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    Data Status Mahasiswa - Tahun Angkatan <?php echo e($year); ?>

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
                                <?php
                                    $totalMahasiswa = 0;
                                    $totalBelumLulus = 0;
                                    $totalLulus = 0;
                                ?>
                                <?php $__currentLoopData = $result1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $totalMahasiswa += $item->total_mahasiswa;
                                        if ($item->jenis_status == 'Belum Lulus') {
                                            $totalBelumLulus += $item->total_mahasiswa;
                                        } elseif ($item->jenis_status == 'Lulus') {
                                            $totalLulus += $item->total_mahasiswa;
                                        }
                                    ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td></td>
                                    <td><strong><?php echo e($totalMahasiswa); ?></strong></td>
                                    <td><strong><?php echo e($totalBelumLulus); ?></strong></td>
                                    <td><strong><?php echo e($totalLulus); ?></strong></td>
                                    <td><strong><?php echo e(round($entropyTotal1, 4)); ?></strong></td>
                                    <td></td>
                                </tr>

                                
                                
                                <tr>
                                    <td class="fw-bold fs-5">Jenis Sekolah</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                            </tr>
                            <?php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total3 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            ?>

                            <!-- Menampilkan data berdasarkan status -->
                            <?php $__currentLoopData = $total3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($kategori); ?></td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong><?php echo e($item['total_mahasiswa']); ?></strong></td>
                                    <td><strong><?php echo e($item['belum_lulus']); ?></strong></td>
                                    <td><strong><?php echo e($item['lulus']); ?></strong></td>
                                    <td><strong><?php echo e(round($item['entropy'], 4)); ?></strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    <?php if($loop->first): ?> <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong><?php echo e(round($item['gain_45'], 4)); ?></strong></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                
                            <tr>
                                <td class="fw-bold fs-5">Satuan Kredit Semester</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total5 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            ?>

                            <!-- Menampilkan data berdasarkan status -->
                            <?php $__currentLoopData = $total5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($kategori); ?></td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong><?php echo e($item['total_mahasiswa']); ?></strong></td>
                                    <td><strong><?php echo e($item['belum_lulus']); ?></strong></td>
                                    <td><strong><?php echo e($item['lulus']); ?></strong></td>
                                    <td><strong><?php echo e(round($item['entropy'], 4)); ?></strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    <?php if($loop->first): ?> <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong><?php echo e(round($item['gain_45'], 4)); ?></strong></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td class="fw-bold fs-5">Jenis Indeks Prestasi Kumulatif</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total6 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            ?>

                            <!-- Menampilkan data berdasarkan status -->
                            <?php $__currentLoopData = $total6; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($kategori); ?></td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong><?php echo e($item['total_mahasiswa']); ?></strong></td>
                                    <td><strong><?php echo e($item['belum_lulus']); ?></strong></td>
                                    <td><strong><?php echo e($item['lulus']); ?></strong></td>
                                    <td><strong><?php echo e(round($item['entropy'], 4)); ?></strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    <?php if($loop->first): ?> <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong><?php echo e(round($item['gain_45'], 4)); ?></strong></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td class="fw-bold fs-5">Jenis Praktek Kerja Lapangan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total7 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            ?>

                            <!-- Menampilkan data berdasarkan status -->
                            <?php $__currentLoopData = $total7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($kategori); ?></td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong><?php echo e($item['total_mahasiswa']); ?></strong></td>
                                    <td><strong><?php echo e($item['belum_lulus']); ?></strong></td>
                                    <td><strong><?php echo e($item['lulus']); ?></strong></td>
                                    <td><strong><?php echo e(round($item['entropy'], 4)); ?></strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    <?php if($loop->first): ?> <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong><?php echo e(round($item['gain_45'], 4)); ?></strong></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td class="fw-bold fs-5">Jenis Kuliah Kerja Nyata</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total8 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            ?>

                            <!-- Menampilkan data berdasarkan status -->
                            <?php $__currentLoopData = $total8; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($kategori); ?></td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong><?php echo e($item['total_mahasiswa']); ?></strong></td>
                                    <td><strong><?php echo e($item['belum_lulus']); ?></strong></td>
                                    <td><strong><?php echo e($item['lulus']); ?></strong></td>
                                    <td><strong><?php echo e(round($item['entropy'], 4)); ?></strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    <?php if($loop->first): ?> <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong><?php echo e(round($item['gain_45'], 4)); ?></strong></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td class="fw-bold fs-5">Jenis Seminar</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                                // Inisialisasi total untuk setiap status
                                $totalBelumLulus = 0;
                                $totalLulus = 0;

                                // Loop untuk menghitung total mahasiswa berdasarkan status
                                foreach ($total9 as $kategori => $item) {
                                    // Pastikan 'belum_lulus' dan 'lulus' ada dalam item
                                    $totalBelumLulus += $item['belum_lulus'] ?? 0; // Menggunakan null coalescing operator
                                    $totalLulus += $item['lulus'] ?? 0; // Menggunakan null coalescing operator
                                }
                            ?>

                            <!-- Menampilkan data berdasarkan status -->
                            <?php $__currentLoopData = $total9; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($kategori); ?></td> <!-- Menampilkan status mahasiswa -->
                                    <td><strong><?php echo e($item['total_mahasiswa']); ?></strong></td>
                                    <td><strong><?php echo e($item['belum_lulus']); ?></strong></td>
                                    <td><strong><?php echo e($item['lulus']); ?></strong></td>
                                    <td><strong><?php echo e(round($item['entropy'], 4)); ?></strong></td>
                                    <!-- Menampilkan nilai gain hanya pada baris pertama -->
                                    <?php if($loop->first): ?> <!-- Menggunakan $loop->first untuk memeriksa apakah ini adalah iterasi pertama -->
                                        <td><strong><?php echo e(round($item['gain_45'], 4)); ?></strong></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                
                                
                            
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
                    <pre><?php echo e($outputData['tree_text']); ?></pre>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Kesimpulan</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($outputData['first_true_path'])): ?>
                        <?php
                            $firstPath = $outputData['first_true_path'][0][0];
                            // Mendapatkan nama kategori dari first_true_path
                            $kategori = explode('_', $firstPath)[1]; // Mengambil bagian kategori dari string
                            $kolom = $columnMapping['kategori_' . $kategori]; // Mendapatkan kolom yang sesuai
                        ?>
                        <p>Jika <?php echo e($firstPath); ?> (<?php echo e($kolom); ?> memenuhi syarat) dan kombinasi nilai pada atribut lain seperti:</p>
                        <ul>
                            <?php $__currentLoopData = $outputData['first_true_path']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <?php echo e($path[0]); ?> 
                                    <?php if($index == 0): ?>
                                        memenuhi syarat
                                    <?php else: ?>
                                        <?php echo e($path[1] >= 0 ? '<=' : '>'); ?> <?php echo e(abs($path[1])); ?> memenuhi syarat
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <p>Tidak ada jalur yang mengarah ke klasifikasi True.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
            <div class="card-header">
                <strong>Data Prediksi Kelulusan Mahasiswa <?php echo e(isset($predictedLulus) ? $predictedLulus : 0); ?> - Tahun Angkatan <?php echo e($year); ?></strong>
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
                                <td><?php echo e(isset($evaluation['TP']) ? $evaluation['TP'] : '-'); ?></td>
                                <td><?php echo e(isset($evaluation['FN']) ? $evaluation['FN'] : '-'); ?></td>
                            </tr>
                            <tr>
                                <td>Sebenarnya Tidak Lulus</td>
                                <td><?php echo e(isset($evaluation['FP']) ? $evaluation['FP'] : '-'); ?></td>
                                <td><?php echo e(isset($evaluation['TN']) ? $evaluation['TN'] : '-'); ?></td>
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
                                <td><?php echo e(isset($evaluation['precision']) ? number_format($evaluation['precision'] * 100, 2) . '%' : '-'); ?></td>
                                <td><?php echo e(isset($evaluation['accuracy']) ? number_format($evaluation['accuracy'] * 100, 2) . '%' : '-'); ?></td>
                                <td><?php echo e(isset($evaluation['recall']) ? number_format($evaluation['recall'] * 100, 2) . '%' : '-'); ?></td>
                                <td><?php echo e(isset($evaluation['f1_score']) ? number_format($evaluation['f1_score'] * 100, 2) . '%' : '-'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       

    </section>
</div>
<?php $__env->stopSection(); ?>

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
<?php echo $__env->make('kerangka.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\skripsi\resources\views/algoritma/klasifikasi_c45_dan_prediksi_matakuliah_teknik_informatika_mahasiswa.blade.php ENDPATH**/ ?>