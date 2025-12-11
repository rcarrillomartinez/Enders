

<?php $__env->startSection('title', 'Reservas del Hotel - Admin'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Reservas de <?php echo e($hotel->nombre_hotel); ?></h4>
                </div>
                <div class="card-body">
                    <h5>Totales por mes</h5>
                    <table class="table mb-4">
                        <thead><tr><th>Mes</th><th>Total comisión (€)</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $monthly; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($month); ?></td>
                                    <td><?php echo e(number_format($total,2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <h5>Reservas (ordenadas por comisión)</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Localizador</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Comisión (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reservas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($r->localizador); ?></td>
                                    <td><?php echo e($r->nombre_cliente ?? $r->email_cliente); ?></td>
                                    <td>
                                        <?php if($r->fecha_reserva): ?>
                                            <?php echo e(is_string($r->fecha_reserva) ? \Carbon\Carbon::parse($r->fecha_reserva)->format('Y-m-d H:i') : $r->fecha_reserva->format('Y-m-d H:i')); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($r->tipoReserva->nombre ?? '-'); ?></td>
                                    <td><?php echo e(number_format($r->commission(),2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/admin/hotel_reservas.blade.php ENDPATH**/ ?>