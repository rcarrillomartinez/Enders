

<?php $__env->startSection('title', 'Crear Reserva'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Crear Reserva</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('hotel.reservas.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="id_tipo_reserva" class="form-label">Tipo de reserva</label>
                            <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select">
                                <option value="">-- Selecciona --</option>
                                <?php $__currentLoopData = \App\Models\TipoReserva::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($t->id_tipo_reserva); ?>"><?php echo e($t->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="email_cliente" class="form-label">Email cliente</label>
                            <input type="email" name="email_cliente" id="email_cliente" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_cliente" class="form-label">Nombre cliente</label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="apellido1_cliente" class="form-label">Apellido 1</label>
                            <input type="text" name="apellido1_cliente" id="apellido1_cliente" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="fecha_entrada" class="form-label">Fecha entrada</label>
                            <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="hora_entrada" class="form-label">Hora entrada</label>
                            <input type="time" name="hora_entrada" id="hora_entrada" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="id_vehiculo" class="form-label">Vehículo (opcional)</label>
                            <select name="id_vehiculo" id="id_vehiculo" class="form-select">
                                <option value="">-- Ninguno --</option>
                                <?php $__currentLoopData = $vehiculos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($v->id_vehiculo); ?>"><?php echo e($v->descripcion); ?> (<?php echo e($v->capacidad); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <button class="btn btn-primary">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/hotel/reservas/create.blade.php ENDPATH**/ ?>