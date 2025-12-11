

<?php $__env->startSection('title','Comisiones mensuales'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white"><h4 class="mb-0">Comisiones mensuales</h4></div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>Year-Month</th><th>Reservas</th><th>Total comisión (€)</th></tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($m['year']); ?>-<?php echo e(str_pad($m['month'],2,'0',STR_PAD_LEFT)); ?></td>
                                    <td><?php echo e($m['count']); ?></td>
                                    <td><?php echo e(number_format($m['total_commission'],2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/hotel/reservas/commissions.blade.php ENDPATH**/ ?>