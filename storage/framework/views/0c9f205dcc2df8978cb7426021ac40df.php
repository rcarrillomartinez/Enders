

<?php $__env->startSection('title', 'Nueva Reserva'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Nueva Reserva</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('reservas.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_cliente" class="form-label">Nombre Cliente *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['nombre_cliente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nombre_cliente" name="nombre_cliente" value="<?php echo e(old('nombre_cliente')); ?>" required>
                                <?php $__errorArgs = ['nombre_cliente'];
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
                                <label for="apellido1_cliente" class="form-label">Apellido 1 *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['apellido1_cliente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="apellido1_cliente" name="apellido1_cliente" value="<?php echo e(old('apellido1_cliente')); ?>" required>
                                <?php $__errorArgs = ['apellido1_cliente'];
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
                                <label for="apellido2_cliente" class="form-label">Apellido 2</label>
                                <input type="text" class="form-control" id="apellido2_cliente" name="apellido2_cliente" value="<?php echo e(old('apellido2_cliente')); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email_cliente" class="form-label">Email *</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email_cliente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email_cliente" name="email_cliente" value="<?php echo e(old('email_cliente', session('user_email') ?? '')); ?>" required>
                                <?php $__errorArgs = ['email_cliente'];
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

                        <hr>

                        <h5>Detalles de la Reserva</h5>

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
                                        <option value="<?php echo e($hotel->id_hotel); ?>" <?php echo e(old('id_hotel') == $hotel->id_hotel ? 'selected' : ''); ?>>
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
                                        <option value="<?php echo e($tipo->id_tipo_reserva); ?>" <?php echo e(old('id_tipo_reserva') == $tipo->id_tipo_reserva ? 'selected' : ''); ?>>
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
                                <label for="fecha_entrada" class="form-label">Fecha de Entrada</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['fecha_entrada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="fecha_entrada" name="fecha_entrada" value="<?php echo e(old('fecha_entrada')); ?>">
                                <?php $__errorArgs = ['fecha_entrada'];
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
                                <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                                <input type="time" class="form-control <?php $__errorArgs = ['hora_entrada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hora_entrada" name="hora_entrada" value="<?php echo e(old('hora_entrada')); ?>">
                                <?php $__errorArgs = ['hora_entrada'];
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
                                <label for="numero_vuelo_entrada" class="form-label">Número de Vuelo Entrada</label>
                                <input type="text" class="form-control" id="numero_vuelo_entrada" name="numero_vuelo_entrada" value="<?php echo e(old('numero_vuelo_entrada')); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="origen_vuelo_entrada" class="form-label">Origen Vuelo Entrada</label>
                                <input type="text" class="form-control" id="origen_vuelo_entrada" name="origen_vuelo_entrada" value="<?php echo e(old('origen_vuelo_entrada')); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_vuelo_salida" class="form-label">Fecha de Salida</label>
                                <input type="date" class="form-control" id="fecha_vuelo_salida" name="fecha_vuelo_salida" value="<?php echo e(old('fecha_vuelo_salida')); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hora_partida" class="form-label">Hora de Partida</label>
                                <input type="time" class="form-control" id="hora_partida" name="hora_partida" value="<?php echo e(old('hora_partida')); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="num_viajeros" class="form-label">Número de Viajeros</label>
                                <input type="number" class="form-control" id="num_viajeros" name="num_viajeros" value="<?php echo e(old('num_viajeros')); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_vehiculo" class="form-label">Vehículo</label>
                                <select class="form-select" id="id_vehiculo" name="id_vehiculo">
                                    <option value="">Selecciona un vehículo</option>
                                    <?php $__currentLoopData = $vehiculos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehiculo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vehiculo->id_vehiculo); ?>" <?php echo e(old('id_vehiculo') == $vehiculo->id_vehiculo ? 'selected' : ''); ?>>
                                            <?php echo e($vehiculo->descripcion); ?> <?php if(isset($vehiculo->capacidad)): ?> (capacidad: <?php echo e($vehiculo->capacidad); ?>) <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Crear Reserva</button>
                            <a href="<?php echo e(route('reservas.index')); ?>" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/reservas/create.blade.php ENDPATH**/ ?>