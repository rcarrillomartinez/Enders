

<?php $__env->startSection('title', 'Reservas del Hotel'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Reservas</h4>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Localizador</th>
                                <th>Cliente</th>
                                <th>Fecha Reserva</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Comisión (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reservas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($r->localizador); ?></td>
                                    <td><?php echo e($r->nombre_cliente ?? $r->email_cliente); ?></td>
                                    <td><?php echo e(optional($r->fecha_reserva)->format('Y-m-d H:i')); ?></td>
                                    <td><?php echo e($r->tipoReserva->nombre ?? '-'); ?></td>
                                    <td><?php echo e($r->estado); ?></td>
                                    <td><?php echo e(number_format($r->commission(), 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/hotel/reservas/index.blade.php ENDPATH**/ ?>