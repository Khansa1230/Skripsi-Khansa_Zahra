

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>
                    <a href="<?php echo e(route('klasifikasi_c45_mahasiswa_agribisnis')); ?>" style="text-decoration: none; color: inherit;">Klasifikasi Algoritma C45 Jurusan Agribisnis</a>
                     - Prediksi Jurusan Agribisnis
                </h4>
            </div>
            <form method="GET" action="<?php echo e(route('prediksi_mahasiswa_agribisnis')); ?>" id="filter-form">
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
                        <?php
                            // Menghitung nilai uji berdasarkan nilai yang dipilih
                            $predictedLulus = old('predicted_lulus', $predictedLulus);
                            $trainingValue = $predictedLulus;
                            $testingValue = 100 - $predictedLulus; // Misalkan total 100
                        ?>

                        <label for="predicted_lulus" class="mb-0 mr-3">
                            Training <?php echo e($trainingValue); ?> dan Uji <?php echo e($testingValue); ?>:
                        </label>
                        <select name="predicted_lulus" id="predicted_lulus" class="form-control w-auto">
                            <option value="">Pilih Prediksi Lulus</option> <!-- Opsi default -->
                            <?php $__currentLoopData = range(10, 100, 10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <!-- Menghasilkan opsi dari 10 hingga 100 -->
                                <option value="<?php echo e($value); ?>" <?php echo e(old('predicted_lulus', $predictedLulus) == $value ? 'selected' : ''); ?>>
                                    <?php echo e($value); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <pre><?php echo e($outputData['tree_text']); ?></pre>
            </div>
        </div>

        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Kesimpulan</h5>
            </div>
            <div class="card-body">
                <?php
                    // Debugging: Lihat isi dari outputData
                    // Ini akan menghentikan eksekusi dan menampilkan data
                    // dd($outputData);
                ?>

                <?php if(!empty($outputData['first_true_path'])): ?>
                    <?php
                        // Ambil kondisi pertama
                        $firstPath = $outputData['first_true_path'][0];
                        $firstCondition = $firstPath[0]; // Kategori pertama
                        $firstKolom = $columnMapping['kategori_' . explode('_', $firstCondition)[1]]; // Mendapatkan kolom
                    ?>
                    <p>Jika <?php echo e($firstCondition); ?> (<?php echo e($firstKolom); ?> memenuhi syarat) dan kombinasi nilai pada atribut lain seperti:</p>
                    <ul>
                        <?php $__currentLoopData = $outputData['first_true_path']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <?php echo e($path[0]); ?> 
                                <?php echo e($index == 0 ? 'memenuhi syarat' : ($path[1] >= 0 ? '<=' : '>') . ' ' . abs($path[1]) . ' memenuhi syarat'); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p>Belum bisa memberikan kesimpulan</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card untuk menampilkan akurasi -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Akurasi Model</h5>
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
                                <td><?php echo e(isset($outputData['TP']) ? $outputData['TP'] : '-'); ?></td>
                                <td><?php echo e(isset($outputData['FN']) ? $outputData['FN'] : '-'); ?></td>
                            </tr>
                            <tr>
                                <td>Sebenarnya Tidak Lulus</td>
                                <td><?php echo e(isset($outputData['FP']) ? $outputData['FP'] : '-'); ?></td>
                                <td><?php echo e(isset($outputData['TN']) ? $outputData['TN'] : '-'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel 2 -->
                <div class="table-responsive">
                    <h5 class="mb-3"><strong>Hasil Akurasi Model</strong></h5>
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
                                <td><strong><?php echo e(number_format($outputData['precision'], 2)); ?>%</strong></td>
                                <td><strong><?php echo e(number_format($outputData['accuracy'], 2)); ?>%</strong></td>
                                <td><strong><?php echo e(number_format($outputData['recall'], 2)); ?>%</strong></td>
                                <td><strong><?php echo e(number_format($outputData['f1_score'], 2)); ?>%</strong></td>
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
    const yearSelect = document.getElementById ('year');
    const storedYear = localStorage.getItem('selectedYear');
    if (storedYear) {
        yearSelect.value = storedYear;
    }
    yearSelect.addEventListener('change', function() {
        localStorage.setItem('selectedYear', this.value);
    });
</script>
<?php echo $__env->make('kerangka.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\skripsi\resources\views/algoritma/prediksi/prediksi_matakuliah_agribisnis_mahasiswa.blade.php ENDPATH**/ ?>