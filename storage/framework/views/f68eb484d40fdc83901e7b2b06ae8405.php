

<?php $__env->startSection('title', 'Detalles de Reserva'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Detalles de la Reserva</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Localizador</h6>
                            <p><strong><?php echo e($reserva->localizador); ?></strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Estado</h6>
                            <p>
                                <span class="badge bg-<?php echo e($reserva->estado === 'confirmada' ? 'success' : ($reserva->estado === 'cancelada' ? 'danger' : 'warning')); ?>">
                                    <?php echo e(ucfirst($reserva->estado)); ?>

                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h5>Información del Cliente</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Nombre</h6>
                            <p><?php echo e($reserva->nombre_cliente); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Apellidos</h6>
                            <p><?php echo e($reserva->apellido1_cliente); ?> <?php echo e($reserva->apellido2_cliente); ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Email</h6>
                            <p><?php echo e($reserva->email_cliente); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Número de Viajeros</h6>
                            <p><?php echo e($reserva->num_viajeros ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <h5>Información de la Reserva</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Hotel</h6>
                            <p><?php echo e($reserva->hotel->nombre_hotel ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Tipo de Reserva</h6>
                            <p><?php echo e($reserva->tipoReserva->nombre ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Fecha Entrada</h6>
                            <p><?php echo e($reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y H:i') : 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Vuelo Entrada</h6>
                            <p><?php echo e($reserva->numero_vuelo_entrada ?? 'N/A'); ?> (<?php echo e($reserva->origen_vuelo_entrada ?? 'N/A'); ?>)</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Fecha Salida</h6>
                            <p><?php echo e($reserva->fecha_vuelo_salida ? \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') : 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Vehículo</h6>
                            <p><?php echo e($reserva->vehiculo->tipo_vehiculo ?? 'N/A'); ?> (<?php echo e($reserva->vehiculo->matricula ?? 'N/A'); ?>)</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Fecha de Reserva</h6>
                            <p><?php echo e(\Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Última Modificación</h6>
                            <p><?php echo e(\Carbon\Carbon::parse($reserva->fecha_modificacion)->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <a href="<?php echo e(route('reservas.index')); ?>" class="btn btn-outline-primary w-100 mb-2">Volver a Reservas</a>
                    <?php if(session('user_type') === 'admin'): ?>
                        <a href="<?php echo e(route('reservas.edit', $reserva->id_reserva)); ?>" class="btn btn-warning w-100 mb-2">Editar</a>
                        <form action="<?php echo e(route('reservas.destroy', $reserva->id_reserva)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/reservas/show.blade.php ENDPATH**/ ?>