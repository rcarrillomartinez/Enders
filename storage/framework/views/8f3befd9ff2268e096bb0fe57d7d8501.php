

<?php $__env->startSection('title', 'Mi Perfil'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Mi Perfil</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <?php if(session('user_type') === 'viajero'): ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo e($user->email ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo e($user->nombre ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="apellido1" class="form-label">Apellido 1</label>
                                <input type="text" class="form-control" value="<?php echo e($user->apellido1 ?? ''); ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" value="<?php echo e($user->ciudad ?? ''); ?>" disabled>
                            </div>
                        <?php elseif(session('user_type') === 'hotel'): ?>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" value="<?php echo e($user->usuario ?? ''); ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="nombre_hotel" class="form-label">Nombre del Hotel</label>
                                <input type="text" class="form-control" id="nombre_hotel" name="nombre_hotel" value="<?php echo e($user->nombre_hotel ?? ''); ?>">
                            </div>
                        <?php elseif(session('user_type') === 'admin'): ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo e($user->email ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo e($user->nombre ?? ''); ?>">
                            </div>
                            <div class="mt-3">
                                <a href="<?php echo e(route('admin.hotels.create')); ?>" class="btn btn-secondary">Crear hotel</a>
                            </div>
                        <?php endif; ?>

                        <hr>

                        <h5>Cambiar Contraseña</h5>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/profile/show.blade.php ENDPATH**/ ?>