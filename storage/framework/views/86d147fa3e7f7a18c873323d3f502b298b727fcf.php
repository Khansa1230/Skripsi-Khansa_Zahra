

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <section class="section">
        <div class="container">

            <!-- Dropdown Tahun Angkatan -->
            <div class="form-group">
                <label for="year">Tahun Angkatan:</label>
                <form action="<?php echo e(route('utama')); ?>" method="GET"> <!-- Ganti 'nama.route.anda' dengan route yang sesuai -->
                    <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                        <option value="Semua" <?php echo e($year == 'Semua' ? 'selected' : ''); ?>>Semua</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yearOption->year); ?>" <?php echo e($year == $yearOption->year ? 'selected' : ''); ?>>
                            <?php echo e($yearOption->year); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
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
                    <?php if(!empty($outputData['first_true_path'])): ?>
                        <?php
                            $firstPath = $outputData['first_true_path'][0][0];
                            $kategori = explode('_', $firstPath)[1];
                            $kolom = $columnMapping['kategori_' . $kategori];
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

        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('kerangka.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\skripsi\resources\views/dashboard/utama.blade.php ENDPATH**/ ?>