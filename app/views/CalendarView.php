<?php

// Asume que $reservas, $currentMonth, $currentYear están definidos.

$currentUser = Auth::getCurrentUser();
$userType = $currentUser['user_type'] ?? 'guest';

// Lógica de generación del calendario (simplificada)
$date = new DateTime("$currentYear-$currentMonth-01");
$daysInMonth = (int)$date->format('t');
$startDayOfWeek = (int)$date->format('w'); // 0 (Dom) a 6 (Sáb)
$monthName = $date->format('F'); 

// Mapear reservas por día para acceso rápido
$reservasByDay = [];
foreach ($reservas as $res) {
    $day = (int)(new DateTime($res['fecha_entrada']))->format('j');
    if (!isset($reservasByDay[$day])) $reservasByDay[$day] = [];
    $reservasByDay[$day][] = $res;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Isla Transfers - Calendario de Gestión</title>
    <style>
        /* Estilos básicos para el calendario */
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; margin-top: 20px; }
        .day { border: 1px solid #ccc; padding: 10px; min-height: 80px; }
        .day-header { background: #eee; text-align: center; font-weight: bold; }
        .reserva-item { background: #667eea; color: white; padding: 2px 5px; margin-top: 5px; cursor: pointer; border-radius: 3px; font-size: 0.8em; }
        .menu-bar { background: #f0f0f0; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗓️ Gestión de Transfers - <?= $monthName ?> <?= $currentYear ?></h1>

        <?php if ($message): ?>
            <div class="alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="menu-bar">
            <span>Usuario: <strong><?= htmlspecialchars($currentUser['user_name']) ?></strong> (<?= ucfirst($userType) ?>)</span>
            <div>
                <a href="?action=index">Ver Calendario</a>
                |
                <?php if ($userType === 'admin' || $userType === 'viajero'): ?>
                    <a href="?action=create">➕ Nueva Reserva</a>
                    |
                <?php endif; ?>

                <?php if ($userType === 'admin'): ?>
                    <a href="?action=management">⚙️ Gestión (Admin)</a>
                    |
                <?php endif; ?>

                <a href="?action=profile">👤 Mi Perfil</a>
                |
                <a href="?action=logout">🚪 Cerrar sesión</a>
            </div>
        </div>
        <div class="calendar-nav">
            <?php 
                $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
                $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
                $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
                $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
            ?>
            <a href="?action=index&month=<?= $prevMonth ?>&year=<?= $prevYear ?>">&lt; Anterior</a>
            <a href="?action=index&month=<?= $nextMonth ?>&year=<?= $nextYear ?>">Siguiente &gt;</a>
        </div>

        <div class="calendar-grid">
            <div class="day-header">Dom</div> <div class="day-header">Lun</div> <div class="day-header">Mar</div> 
            <div class="day-header">Mié</div> <div class="day-header">Jue</div> <div class="day-header">Vie</div> 
            <div class="day-header">Sáb</div> 

            <?php 
            // Celdas vacías al inicio
            for ($i = 0; $i < $startDayOfWeek; $i++) echo "<div class=\"day\"></div>"; 
            
            // Días del mes
            for ($day = 1; $day <= $daysInMonth; $day++): ?>
                <div class="day">
                    <strong><?= $day ?></strong>
                    <?php if (isset($reservasByDay[$day])): ?>
                        <?php foreach ($reservasByDay[$day] as $reserva): ?>
                            <div class="reserva-item" onclick="showReservaDetail(<?= htmlspecialchars(json_encode($reserva)) ?>, '<?= $userType ?>')">
                                <?= (new DateTime($reserva['hora_entrada']))->format('H:i') ?> | <?= $reserva['localizador'] ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <div id="reservaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
        <div style="background: white; width: 400px; margin: 50px auto; padding: 20px;">
            <h3 id="modalTitle">Detalle de Reserva</h3>
            <pre id="modalContent"></pre>
            <div id="modalActions"></div>
            <button onclick="document.getElementById('reservaModal').style.display='none'">Cerrar</button>
        </div>
    </div>

    <script>
    function showReservaDetail(reserva, userType) {
        document.getElementById('modalTitle').textContent = 'Reserva: ' + reserva.localizador;
        
        let content = `
            Localizador: ${reserva.localizador}
            Estado: ${reserva.estado}
            Viajeros: ${reserva.num_viajeros}
            Email Cliente: ${reserva.email_cliente}
            
            --- LLEGADA (Aeropuerto -> Hotel) ---
            Día Llegada: ${reserva.fecha_entrada || 'N/A'}
            Hora Vuelo: ${reserva.hora_entrada || 'N/A'}
            Vuelo: ${reserva.numero_vuelo_entrada || 'N/A'}
            Origen: ${reserva.origen_vuelo_entrada || 'N/A'}
            
            --- SALIDA (Hotel -> Aeropuerto) ---
            Día Vuelo Salida: ${reserva.fecha_vuelo_salida || 'N/A'}
            Hora Vuelo Salida: ${reserva.hora_vuelo_salida || 'N/A'}
            
            Hotel: ${reserva.hotel_nombre || 'N/A'}
            Viajero principal: ${reserva.viajero_nombre || 'N/A'}
        `;
        
        document.getElementById('modalContent').textContent = content;
        
        let actionsHtml = '';
        if (userType === 'admin' || (userType === 'viajero' && reserva.estado === 'pendiente')) {
             actionsHtml = `
                <a href="?action=edit&id=${reserva.id_reserva}">[Modificar]</a> |
                <a href="?action=cancel&id=${reserva.id_reserva}" onclick="return confirm('¿Seguro que quieres cancelar?');">[Cancelar]</a>
             `;
        }
        document.getElementById('modalActions').innerHTML = actionsHtml;

        document.getElementById('reservaModal').style.display = 'block';
    }
    </script>
</body>
</html>
