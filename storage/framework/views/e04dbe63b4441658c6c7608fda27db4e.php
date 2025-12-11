

<?php $__env->startSection('title', 'Hoteles - Admin'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestión de Hoteles</h4>
                    <a href="<?php echo e(route('admin.hotels.create')); ?>" class="btn btn-sm btn-success">+ Crear Hotel</a>
                </div>
                <div class="card-body">
                    <?php if($hotels->isEmpty()): ?>
                        <div class="alert alert-info">No hay hoteles registrados.</div>
                    <?php else: ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre del Hotel</th>
                                    <th>Usuario</th>
                                    <th>Comisión (€)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($hotel->nombre_hotel); ?></td>
                                        <td><?php echo e($hotel->usuario); ?></td>
                                        <td><?php echo e($hotel->comision); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.hotels.reservas', $hotel->id_hotel)); ?>" class="btn btn-sm btn-primary">
                                                Ver Reservas
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/admin/hotels_list.blade.php ENDPATH**/ ?>