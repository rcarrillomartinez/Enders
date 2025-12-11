

<?php $__env->startSection('title', 'Mis Reservas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col">
            <h2>Mis Reservas</h2>
        </div>
        <div class="col-auto">
            <?php if(session('user_type') === 'admin'): ?>
                <a href="<?php echo e(route('admin.hotels.create')); ?>" class="btn btn-secondary">Crear hotel</a>                             
                <a href="<?php echo e(route('admin.hotels.list')); ?>" class="btn btn-primary">Gestionar Hoteles</a>
            <?php endif; ?>
            <?php if(session('user_type') !== 'hotel'): ?>
                <a href="<?php echo e(route('reservas.create')); ?>" class="btn btn-primary">Nueva Reserva</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if($reservas->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Localizador</th>
                        <th>Hotel</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Fecha Entrada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reservas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserva): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($reserva->localizador); ?></strong></td>
                            <td><?php echo e($reserva->hotel->nombre_hotel ?? 'N/A'); ?></td>
                            <td><?php echo e($reserva->tipoReserva->nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($reserva->nombre_cliente); ?></td>
                            <td><?php echo e($reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') : 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($reserva->estado === 'confirmada' ? 'success' : ($reserva->estado === 'cancelada' ? 'danger' : 'warning')); ?>">
                                    <?php echo e(ucfirst($reserva->estado)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('reservas.show', $reserva->id_reserva)); ?>" class="btn btn-sm btn-info">Ver</a>
                                <?php if(session('user_type') === 'admin'): ?>
                                    <a href="<?php echo e(route('reservas.edit', $reserva->id_reserva)); ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="<?php echo e(route('reservas.destroy', $reserva->id_reserva)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <nav>
            <?php echo e($reservas->links()); ?>

        </nav>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            No hay reservas disponibles. 
            <?php if(session('user_type') !== 'hotel'): ?>
                <a href="<?php echo e(route('reservas.create')); ?>">Crea una nueva reserva</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/reservas/index.blade.php ENDPATH**/ ?>