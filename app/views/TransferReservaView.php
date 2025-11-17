<?php

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
            text-align: center;
        }
        .stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }
        .view-switcher {
            display: flex;
            gap: 5px;
        }
        .view-switcher select {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
        }
        .calendar-nav button {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .calendar-nav button:hover {
            background-color: #764ba2;
        }
        .current-month {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            flex: 1;
            text-align: center;
        }
        .calendar {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .calendar th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            font-size: 0.95em;
        }
        .calendar td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            height: 120px;
            vertical-align: top;
            background-color: #fafafa;
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .calendar td:hover {
            background-color: #f0f0f0;
        }
        .calendar td.other-month {
            color: #ccc;
            background-color: #f5f5f5;
        }
        .calendar td.today {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
        }
        .day-number {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 1.1em;
        }
        .day-number.other-month {
            color: #ccc;
        }
        .reservations-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.85em;
        }
        .reservation-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 8px;
            margin-bottom: 4px;
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .reservation-item:hover {
            transform: translateX(2px);
        }
        .reservation-item.confirmada {
            background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%);
        }
        .reservation-item.completada {
            background: linear-gradient(135deg, #1890ff 0%, #0050b3 100%);
        }
        .reservation-item.pendiente {
            background: linear-gradient(135deg, #faad14 0%, #d48806 100%);
        }
        .reservation-item.cancelada {
            background: linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%);
            opacity: 0.7;
            text-decoration: line-through;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.1em;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 15px;
        }
        .modal-header h2 {
            color: #333;
            margin: 0;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
        }
        .modal-body {
            color: #555;
            line-height: 1.8;
        }
        .modal-body p {
            margin-bottom: 12px;
        }
        .modal-body strong {
            color: #333;
            display: inline-block;
            min-width: 120px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
        }
        .status-badge.confirmada {
            background-color: #d4edda;
            color: #155724;
        }
        .status-badge.completada {
            background-color: #cfe2ff;
            color: #084298;
        }
        .status-badge.pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-badge.cancelada {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Calendario de Reservas</h1>
        
        <?php
        require_once __DIR__ . '/../models/Auth.php';
        if (Auth::isLoggedIn()) {
            $user = Auth::getCurrentUser();
            echo '<div class="stats" style="background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%); margin-bottom: 20px;">';
            echo 'Sesión activa: <strong>' . htmlspecialchars($user['user_name']) . '</strong> (' . $user['user_type'] . ') ';
            echo '<a href="?action=logout" style="color: white; text-decoration: underline;">Cerrar sesión</a>';
            echo '</div>';
        } else {
            echo '<div class="stats" style="background: linear-gradient(135deg, #1890ff 0%, #0050b3 100%); margin-bottom: 20px; text-align: center;">';
            echo '<a href="?action=auth" style="color: white; text-decoration: none; font-weight: 600;">🔐 Iniciar sesión o Registrarse</a>';
            echo '</div>';
        }
        ?>
        
        <?php
        if (!isset($reservas)) {
            echo '<div class="error"><strong>No data provided.</strong> The controller must supply <code>$reservas</code>.</div>';
        } else {
            $total = $total ?? count($reservas);

            // Filtrar reservas si el usuario es un viajero
            if (Auth::isLoggedIn()) {
                $currentUser = Auth::getCurrentUser();
                if ($currentUser && $currentUser['user_type'] === 'viajero' && isset($currentUser['user_email'])) {
                    $reservas = array_filter($reservas, function($reserva) use ($currentUser) {
                        return isset($reserva['email_cliente']) && $reserva['email_cliente'] === $currentUser['user_email'];
                    });
                }
            }

            if (empty($reservas)) {
                echo '<div class="no-data">No se han encontrado reservas.</div>';
            } else {
                echo '<div class="stats">Total de reservas: <strong>' . htmlspecialchars($total) . '</strong></div>';
                $currentDate = new DateTime();
                if (isset($_GET['date'])) {
                    $currentDate = new DateTime($_GET['date']);
                }

                $view = $_GET['view'] ?? 'month';
                $today = new DateTime();
                $todayStr = $today->format('Y-m-d');

                // Organizar las reservas por fecha_entrada
                $reservasByDate = [];
                foreach ($reservas as $reserva) {
                    $fecha = $reserva['fecha_entrada'] ?? null;
                    if ($fecha) {
                        if (!isset($reservasByDate[$fecha])) {
                            $reservasByDate[$fecha] = [];
                        }
                        $reservasByDate[$fecha][] = $reserva;
                    }
                }

                $months_es = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                $days_es = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

                $prevDate = clone $currentDate;
                $nextDate = clone $currentDate;
                $headerText = '';

                if ($view === 'month') {
                    $currentDate->modify('first day of this month');
                    $prevDate->modify('first day of this month')->modify('-1 month');
                    $nextDate->modify('first day of this month')->modify('+1 month');
                    $headerText = $months_es[$currentDate->format('n') - 1] . ' de ' . $currentDate->format('Y');
                } elseif ($view === 'week') {
                    $currentDate->modify('monday this week');
                    $prevDate->modify('monday this week')->modify('-1 week');
                    $nextDate->modify('monday this week')->modify('+1 week');
                    $endOfWeek = (clone $currentDate)->modify('+6 days');
                    $headerText = 'Semana del ' . $currentDate->format('d/m/Y') . ' al ' . $endOfWeek->format('d/m/Y');
                } else {
                    $prevDate->modify('-1 day');
                    $nextDate->modify('+1 day');
                    $headerText = $days_es[$currentDate->format('N') - 1] . ', ' . $currentDate->format('d') . ' de ' . $months_es[$currentDate->format('n') - 1] . ' de ' . $currentDate->format('Y');
                }

                echo '<div class="calendar-nav">';
                echo '<button onclick="location.href=\'?action=index&view=' . $view . '&date=' . $prevDate->format('Y-m-d') . '\'">← Anterior</button>';
                echo '<div class="current-month">' . $headerText . '</div>';
                echo '<div class="view-switcher">';
                echo '<select id="view-selector" onchange="changeView(this)">';
                echo '<option value="month"' . ($view === 'month' ? ' selected' : '') . '>Mes</option>';
                echo '<option value="week"' . ($view === 'week' ? ' selected' : '') . '>Semana</option>';
                echo '<option value="day"' . ($view === 'day' ? ' selected' : '') . '>Día</option>';
                echo '</select>';
                echo '</div>';
                echo '<button onclick="location.href=\'?action=index&view=' . $view . '&date=' . $nextDate->format('Y-m-d') . '\'">Siguiente →</button>';
                echo '</div>';

                // Render calendar based on view
                if ($view === 'month') {
                    // Crea el calendario mensual
                    $firstDayOfMonth = (clone $currentDate)->modify('first day of this month');
                    $lastDayOfMonth = (clone $currentDate)->modify('last day of this month');
                    
                    echo '<table class="calendar">';
                    echo '<thead><tr>';
                    foreach ($days_es as $dayName) {
                        echo '<th>' . $dayName . '</th>';
                    }
                    echo '</tr></thead>';
                    echo '<tbody><tr>';
                    
                    $startDayOfWeek = $firstDayOfMonth->format('N') - 1;
                    $daysInMonth = (int)$lastDayOfMonth->format('d');

                    $prevMonthLastDay = (clone $firstDayOfMonth)->modify('-1 day');
                    $prevMonthCellStart = (int)$prevMonthLastDay->format('d') - $startDayOfWeek + 1;

                    for ($i = 0; $i < $startDayOfWeek; $i++) {
                        echo '<td class="other-month"><div class="day-number other-month">' . ($prevMonthCellStart + $i) . '</div></td>';
                    }

                    $cellsGenerated = $startDayOfWeek;
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dateStr = $currentDate->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $isToday = $dateStr === $todayStr ? 'today' : '';
                        
                        echo '<td class="' . $isToday . '">';
                        echo '<div class="day-number">' . $day . '</div>';

                        if (isset($reservasByDate[$dateStr])) {
                            echo '<ul class="reservations-list">';
                            foreach ($reservasByDate[$dateStr] as $reserva) {
                                $status = $reserva['estado'] ?? 'pendiente';
                                $localizador = $reserva['localizador'] ?? 'N/A';
                                echo '<li class="reservation-item ' . htmlspecialchars($status) . '" onclick="showReservation(event)" data-reserva=\'' . htmlspecialchars(json_encode($reserva)) . '\'>';
                                echo htmlspecialchars($localizador);
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        echo '</td>';
                        
                        $cellsGenerated++;
                        if ($cellsGenerated % 7 === 0 && $day < $daysInMonth) {
                            echo '</tr><tr>';
                        }
                    }

                    $remainingCells = 7 - ($cellsGenerated % 7);
                    if ($remainingCells !== 7) {
                        for ($day = 1; $day <= $remainingCells; $day++) {
                            echo '<td class="other-month"><div class="day-number other-month">' . $day . '</div></td>';
                        }
                    }

                    echo '</tr></tbody>';
                    echo '</table>';

                } elseif ($view === 'week') { //Vista de semana
                    echo '<table class="calendar">';
                    echo '<thead><tr>';
                    $dayIterator = clone $currentDate;
                    for ($i = 0; $i < 7; $i++) {
                        echo '<th>' . $days_es[$dayIterator->format('N') - 1] . ' ' . $dayIterator->format('d/m') . '</th>';
                        $dayIterator->modify('+1 day');
                    }
                    echo '</tr></thead>';
                    echo '<tbody><tr>';
                    $dayIterator = clone $currentDate;
                    for ($i = 0; $i < 7; $i++) {
                        $dateStr = $dayIterator->format('Y-m-d');
                        $isToday = $dateStr === $todayStr ? 'today' : '';
                        echo '<td class="' . $isToday . '">';
                        echo '<div class="day-number">' . $dayIterator->format('j') . '</div>';
                        if (isset($reservasByDate[$dateStr])) {
                            echo '<ul class="reservations-list">';
                            foreach ($reservasByDate[$dateStr] as $reserva) {
                                $status = $reserva['estado'] ?? 'pendiente';
                                $localizador = $reserva['localizador'] ?? 'N/A';
                                echo '<li class="reservation-item ' . htmlspecialchars($status) . '" onclick="showReservation(event)" data-reserva=\'' . htmlspecialchars(json_encode($reserva)) . '\'>';
                                echo htmlspecialchars($localizador);
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        echo '</td>';
                        $dayIterator->modify('+1 day');
                    }
                    echo '</tr></tbody>';
                    echo '</table>';

                } else { // Vista de dia
                    echo '<table class="calendar">';
                    echo '<thead><tr><th>' . $headerText . '</th></tr></thead>';
                    echo '<tbody><tr>';
                    $dateStr = $currentDate->format('Y-m-d');
                    $isToday = $dateStr === $todayStr ? 'today' : '';
                    echo '<td class="' . $isToday . '" style="height: 60vh;">';
                    if (isset($reservasByDate[$dateStr])) {
                        echo '<ul class="reservations-list">';
                        foreach ($reservasByDate[$dateStr] as $reserva) {
                            $status = $reserva['estado'] ?? 'pendiente';
                            $localizador = $reserva['localizador'] ?? 'N/A';
                            $hora = isset($reserva['hora_recogida']) ? (new DateTime($reserva['hora_recogida']))->format('H:i') : '';
                            echo '<li class="reservation-item ' . htmlspecialchars($status) . '" onclick="showReservation(event)" data-reserva=\'' . htmlspecialchars(json_encode($reserva)) . '\'>';
                            echo '<strong>' . htmlspecialchars($hora) . '</strong> - ' . htmlspecialchars($localizador);
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<div class="no-data">No hay reservas para este día.</div>';
                    }
                    echo '</td>';
                    echo '</tr></tbody>';
                    echo '</table>';
                }

            }
        }
        ?>
    </div>

    <div id="reservaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalles de Reserva</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Detalles de la reserva se ponen aquí -->
            </div>
        </div>
    </div>

    <script>
        function changeView(selector) {
            const newView = selector.value;
            const currentDate = '<?php echo $currentDate->format('Y-m-d'); ?>';
            location.href = `?action=index&view=${newView}&date=${currentDate}`;
        }

        function showReservation(event) {
            const item = event.target.closest('.reservation-item');
            if (!item) return;
            
            const reservaJson = item.getAttribute('data-reserva');
            const reserva = JSON.parse(reservaJson);
            
            let html = '';
            const excludeFields = ['id_reserva', 'id_hotel', 'id_tipo_reserva', 'id_destino', 'id_vehiculo', 'id_viajero', 'id_transfer'];
            
            for (const [key, value] of Object.entries(reserva)) {
                if (excludeFields.includes(key) || key.toLowerCase().includes('id')) continue;
                
                const label = key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ');
                
                if (key === 'estado') {
                    html += '<p><strong>' + label + ':</strong> <span class="status-badge ' + value + '">' + value + '</span></p>';
                } else {
                    html += '<p><strong>' + label + ':</strong> ' + (value || 'N/A') + '</p>';
                }
            }
            
            document.getElementById('modalBody').innerHTML = html;
            document.getElementById('reservaModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('reservaModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('reservaModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
