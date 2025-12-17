

<?php $__env->startSection('title', 'Editar Reserva'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Editar Reserva</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('reservas.update', $reserva->id_reserva)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-3">
                            <label for="localizador" class="form-label">Localizador</label>
                            <input type="text" class="form-control" id="localizador" value="<?php echo e($reserva->localizador); ?>" disabled>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_hotel" class="form-label">Hotel *</label>
                                <select class="form-select <?php $__errorArgs = ['id_hotel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="id_hotel" name="id_hotel" required>
                                    <option value="">Selecciona un hotel</option>
                                    <?php $__currentLoopData = $hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($hotel->id_hotel); ?>" <?php echo e($reserva->id_hotel == $hotel->id_hotel ? 'selected' : ''); ?>>
                                            <?php echo e($hotel->nombre_hotel); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['id_hotel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_tipo_reserva" class="form-label">Tipo de Reserva *</label>
                                <select class="form-select <?php $__errorArgs = ['id_tipo_reserva'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="id_tipo_reserva" name="id_tipo_reserva" required>
                                    <option value="">Selecciona un tipo</option>
                                    <?php $__currentLoopData = $tiposReserva; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tipo->id_tipo_reserva); ?>" <?php echo e($reserva->id_tipo_reserva == $tipo->id_tipo_reserva ? 'selected' : ''); ?>>
                                            <?php echo e($tipo->nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['id_tipo_reserva'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="num_viajeros" class="form-label">Número de Viajeros</label>
                                <input type="number" class="form-control" id="num_viajeros" name="num_viajeros" value="<?php echo e($reserva->num_viajeros); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_vehiculo" class="form-label">Vehículo</label>
                                <select class="form-select" id="id_vehiculo" name="id_vehiculo">
                                    <option value="">Selecciona un vehículo</option>
                                    <?php $__currentLoopData = $vehiculos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehiculo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vehiculo->id_vehiculo); ?>" <?php echo e($reserva->id_vehiculo == $vehiculo->id_vehiculo ? 'selected' : ''); ?>>
                                            <?php echo e($vehiculo->descripcion); ?> <?php if(isset($vehiculo->capacidad)): ?> (capacidad: <?php echo e($vehiculo->capacidad); ?>) <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="estado" name="estado" required>
                                <option value="pendiente" <?php echo e($reserva->estado === 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                <option value="confirmada" <?php echo e($reserva->estado === 'confirmada' ? 'selected' : ''); ?>>Confirmada</option>
                                <option value="cancelada" <?php echo e($reserva->estado === 'cancelada' ? 'selected' : ''); ?>>Cancelada</option>
                                <option value="completada" <?php echo e($reserva->estado === 'completada' ? 'selected' : ''); ?>>Completada</option>
                            </select>
                            <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Actualizar Reserva</button>
                            <a href="<?php echo e(route('reservas.show', $reserva->id_reserva)); ?>" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/reservas/edit.blade.php ENDPATH**/ ?>