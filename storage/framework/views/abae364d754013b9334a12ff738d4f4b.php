<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Enders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        main {
            min-height: calc(100vh - 200px);
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Enders</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(Auth::guard('viajero')->check() || Auth::guard('hotel')->check() || Auth::guard('admin')->check()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('reservas.index')); ?>">Reservas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('reservas.calendar')); ?>">Calendario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('profile.show')); ?>">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <form action="<?php echo e(route('auth.logout')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="nav-link btn btn-link">Cerrar sesión</button>
                            </form>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Error!</h4>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/enders/resources/views/layouts/app.blade.php ENDPATH**/ ?>