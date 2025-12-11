

<?php $__env->startSection('title', 'Panel Hotel'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Panel del Hotel</h4>
                </div>
                <div class="card-body">
                    <p>Bienvenido al panel de hotel. Opciones disponibles:</p>
                    <ul>
                        <li><a href="<?php echo e(route('hotel.reservas.index')); ?>">Ver reservas</a></li>
                        <li><a href="<?php echo e(route('hotel.reservas.create')); ?>">Crear reserva</a></li>
                        <li><a href="<?php echo e(route('hotel.commissions')); ?>">Ver comisiones mensuales</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/hotel/dashboard.blade.php ENDPATH**/ ?>