

<?php $__env->startSection('title', 'Calendario de Reservas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col">
            <h2>Calendario de Reservas</h2>
        </div>
        <div class="col-auto">
            <a href="<?php echo e(route('reservas.index')); ?>" class="btn btn-secondary">Ver Lista</a>
            <a href="<?php echo e(route('reservas.create')); ?>" class="btn btn-primary">Nueva Reserva</a>
        </div>
    </div>

    <!-- Botones para cambiar modo de vista -->
    <div class="row mb-3">
        <div class="col">
            <div class="btn-group" role="group">
                <a href="<?php echo e(route('reservas.calendar', ['view' => 'day', 'date' => $currentDate->format('Y-m-d')])); ?>" 
                   class="btn btn-outline-primary <?php echo e($viewMode === 'day' ? 'active' : ''); ?>">
                    <i class="fa fa-calendar-day"></i> Diario
                </a>
                <a href="<?php echo e(route('reservas.calendar', ['view' => 'week', 'date' => $currentDate->format('Y-m-d')])); ?>" 
                   class="btn btn-outline-primary <?php echo e($viewMode === 'week' ? 'active' : ''); ?>">
                    <i class="fa fa-calendar-week"></i> Semanal
                </a>
                <a href="<?php echo e(route('reservas.calendar', ['view' => 'month', 'date' => $currentDate->format('Y-m-d')])); ?>" 
                   class="btn btn-outline-primary <?php echo e($viewMode === 'month' ? 'active' : ''); ?>">
                    <i class="fa fa-calendar"></i> Mensual
                </a>
            </div>
        </div>
    </div>

    <div class="calendar-container">
        <!-- VISTA DIARIA -->
        <?php if($viewMode === 'day'): ?>
            <?php
                $monthYearNav = $currentDate->copy();
            ?>
            <div class="calendar-header mb-3">
                <div class="row align-items-center">
                    <div class="col">
                        <a href="<?php echo e(route('reservas.calendar', ['view' => 'day', 'date' => $currentDate->copy()->subDay()->format('Y-m-d')])); ?>" 
                           class="btn btn-sm btn-outline-secondary"><i class="fa fa-chevron-left"></i></a>
                        <span class="mx-2 h5">
                            <?php
                                $daysES = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
                                $monthsES = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                                $dayName = $daysES[$currentDate->dayOfWeek];
                                $monthName = $monthsES[$currentDate->month - 1];
                            ?>
                            <?php echo e(ucfirst($dayName)); ?>, <?php echo e($currentDate->day); ?> de <?php echo e($monthName); ?> de <?php echo e($currentDate->year); ?>

                        </span>
                        <a href="<?php echo e(route('reservas.calendar', ['view' => 'day', 'date' => $currentDate->copy()->addDay()->format('Y-m-d')])); ?>" 
                           class="btn btn-sm btn-outline-secondary"><i class="fa fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered day-calendar">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">Hora</th>
                            <th>Reservas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $dateStr = $currentDate->format('Y-m-d');
                            $reservasForDay = $calendarReservas[$dateStr] ?? [];
                        ?>
                        <?php for($hour = 0; $hour < 24; $hour++): ?>
                            <?php
                                $timeStr = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                                $reservasAtHour = collect($reservasForDay)->filter(function($r) use ($hour, $dateStr) {
                                    $entryTime = \Carbon\Carbon::parse($r->fecha_entrada);
                                    return $entryTime->hour == $hour;
                                });
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo e($timeStr); ?></td>
                                <td>
                                    <?php if(count($reservasAtHour) > 0): ?>
                                        <?php $__currentLoopData = $reservasAtHour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserva): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="badge bg-info mb-1" style="display: block; white-space: normal;">
                                                <a href="<?php echo e(route('reservas.show', $reserva->id_reserva)); ?>" class="text-white text-decoration-none">
                                                    <?php echo e($reserva->nombre_cliente); ?> - <?php echo e($reserva->hotel->nombre_hotel ?? 'N/A'); ?>

                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.9rem;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- VISTA SEMANAL -->
        <?php if($viewMode === 'week'): ?>
            <?php
                $weekStart = $currentDate->copy()->startOfWeek();
                $weekEnd = $currentDate->copy()->endOfWeek();
            ?>
            <div class="calendar-header mb-3">
                <div class="row align-items-center">
                    <div class="col">
                        <a href="<?php echo e(route('reservas.calendar', ['view' => 'week', 'date' => $weekStart->copy()->subDay()->format('Y-m-d')])); ?>" 
                           class="btn btn-sm btn-outline-secondary"><i class="fa fa-chevron-left"></i></a>
                        <span class="mx-2 h5">
                            <?php
                                $monthsES = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                                $monthStart = $monthsES[$weekStart->month - 1];
                                $monthEnd = $monthsES[$weekEnd->month - 1];
                            ?>
                            <?php echo e($weekStart->day); ?> de <?php echo e($monthStart); ?> - <?php echo e($weekEnd->day); ?> de <?php echo e($monthEnd); ?> de <?php echo e($weekEnd->year); ?>

                        </span>
                        <a href="<?php echo e(route('reservas.calendar', ['view' => 'week', 'date' => $weekEnd->copy()->addDay()->format('Y-m-d')])); ?>" 
                           class="btn btn-sm btn-outline-secondary"><i class="fa fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered week-calendar">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;">Hora</th>
                            <?php 
                                $daysES = ['lun', 'mar', 'mié', 'jue', 'vie', 'sab', 'dom'];
                                $dayLoop = $weekStart->copy(); 
                            ?>
                            <?php for($i = 0; $i < 7; $i++): ?>
                                <th class="text-center">
                                    <div><?php echo e(ucfirst($daysES[$i])); ?></div>
                                    <small><?php echo e($dayLoop->format('d/m')); ?></small>
                                </th>
                                <?php $dayLoop->addDay(); ?>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($hour = 8; $hour < 20; $hour++): ?>
                            <tr>
                                <td class="fw-bold text-center bg-light"><?php echo e(str_pad($hour, 2, '0', STR_PAD_LEFT)); ?>:00</td>
                                <?php $dayLoop = $weekStart->copy(); ?>
                                <?php for($i = 0; $i < 7; $i++): ?>
                                    <?php
                                        $dayStr = $dayLoop->format('Y-m-d');
                                        $reservasForDay = $calendarReservas[$dayStr] ?? [];
                                        $reservasAtHour = collect($reservasForDay)->filter(function($r) use ($hour, $dayStr) {
                                            $entryTime = \Carbon\Carbon::parse($r->fecha_entrada);
                                            return $entryTime->hour == $hour;
                                        });
                                    ?>
                                    <td class="text-center small" style="min-height: 50px; vertical-align: middle;">
                                        <?php if(count($reservasAtHour) > 0): ?>
                                            <?php $__currentLoopData = $reservasAtHour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserva): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="badge bg-success mb-1" style="display: block; white-space: normal; font-size: 0.7rem;">
                                                    <a href="<?php echo e(route('reservas.show', $reserva->id_reserva)); ?>" class="text-white text-decoration-none">
                                                        <?php echo e(Str::limit($reserva->nombre_cliente, 8)); ?>

                                                    </a>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php $dayLoop->addDay(); ?>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- VISTA MENSUAL -->
        <?php if($viewMode === 'month'): ?>
            <div class="calendar-header mb-3">
                <div class="row align-items-center">
                    <div class="col">
                        <a href="<?php echo e(route('reservas.calendar', ['view' => 'month', 'date' => $currentMonth->copy()->subMonth()->format('Y-m-d')])); ?>" 
                           class="btn btn-sm btn-outline-secondary"><i class="fa fa-chevron-left"></i></a>
                        <span class="mx-2 h5">
                            <?php
                                $monthsES = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                                $monthName = $monthsES[$currentMonth->month - 1];
                            ?>
                            <?php echo e(ucfirst($monthName)); ?> <?php echo e($currentMonth->year); ?>

                        </span>
                        <a href="<?php echo e(route('reservas.calendar', ['view' => 'month', 'date' => $currentMonth->copy()->addMonth()->format('Y-m-d')])); ?>" 
                           class="btn btn-sm btn-outline-secondary"><i class="fa fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered calendar-table">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Lun</th>
                            <th class="text-center">Mar</th>
                            <th class="text-center">Mié</th>
                            <th class="text-center">Jue</th>
                            <th class="text-center">Vie</th>
                            <th class="text-center">Sab</th>
                            <th class="text-center">Dom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $firstDay = $currentMonth->copy()->startOfMonth();
                            $lastDay = $currentMonth->copy()->endOfMonth();
                            $startDate = $firstDay->copy()->startOfWeek();
                            $endDate = $lastDay->copy()->endOfWeek();
                            $loopDate = $startDate->copy();
                        ?>

                        <?php while($loopDate <= $endDate): ?>
                            <tr style="height: 120px;">
                                <?php for($i = 0; $i < 7; $i++): ?>
                                    <?php
                                        $dateStr = $loopDate->format('Y-m-d');
                                        $isCurrentMonth = $loopDate->month === $currentMonth->month;
                                        $hasReserva = isset($calendarReservas[$dateStr]) && count($calendarReservas[$dateStr]) > 0;
                                    ?>
                                    <td class="calendar-day <?php echo e($isCurrentMonth ? '' : 'bg-light'); ?> <?php echo e($hasReserva ? 'has-reserva' : ''); ?> p-2" style="vertical-align: top; overflow-y: auto;">
                                        <div class="date-number fw-bold mb-1">
                                            <a href="<?php echo e(route('reservas.calendar', ['view' => 'day', 'date' => $loopDate->format('Y-m-d')])); ?>" class="text-decoration-none">
                                                <?php echo e($loopDate->day); ?>

                                            </a>
                                        </div>
                                        <?php if($hasReserva): ?>
                                            <div class="reservas-in-day">
                                                <?php $__currentLoopData = $calendarReservas[$dateStr]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserva): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="badge bg-primary mb-1" style="font-size: 0.75rem; display: block; white-space: normal;">
                                                        <a href="<?php echo e(route('reservas.show', $reserva->id_reserva)); ?>" class="text-white text-decoration-none" title="<?php echo e($reserva->nombre_cliente); ?>">
                                                            <?php echo e(Str::limit($reserva->nombre_cliente, 12)); ?>

                                                        </a>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <?php $loopDate->addDay(); ?>
                                <?php endfor; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    </div>

    <style>
        .calendar-table {
            table-layout: fixed;
        }
        .calendar-day {
            border: 1px solid #dee2e6;
        }
        .calendar-day.has-reserva {
            background-color: #f0f8ff;
        }
        .date-number {
            font-size: 1.1rem;
        }
        .reservas-in-day {
            max-height: 100px;
            overflow-y: auto;
        }
        .calendar-container {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .day-calendar th,
        .day-calendar td {
            padding: 8px;
        }
        .day-calendar tbody tr:hover {
            background-color: #f5f5f5;
        }
        .week-calendar {
            table-layout: fixed;
            font-size: 0.9rem;
        }
        .week-calendar td {
            padding: 4px;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-group .btn {
            border-radius: 4px;
            margin-right: 4px;
        }
        .btn-group .btn.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/enders/resources/views/reservas/calendar.blade.php ENDPATH**/ ?>