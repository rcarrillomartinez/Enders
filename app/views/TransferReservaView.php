<?php

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Reservas - Calendario</title>
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
        if (!isset($reservas)) {
            echo '<div class="error"><strong>No data provided.</strong> The controller must supply <code>$reservas</code>.</div>';
        } else {
            $total = $total ?? count($reservas);

            if (empty($reservas)) {
                echo '<div class="no-data">No se han encontrado reservas.</div>';
            } else {
                echo '<div class="stats">Total de reservas: <strong>' . htmlspecialchars($total) . '</strong></div>';

                // Get current month or from URL parameter
                $currentDate = new DateTime();
                if (isset($_GET['month']) && isset($_GET['year'])) {
                    $currentDate = new DateTime($_GET['year'] . '-' . str_pad($_GET['month'], 2, '0', STR_PAD_LEFT) . '-01');
                }

                // Group reservations by fecha_entrada
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

                // Generate calendar
                $year = (int)$currentDate->format('Y');
                $month = (int)$currentDate->format('m');
                $firstDay = new DateTime("$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01");
                $lastDay = clone $firstDay;
                $lastDay->modify('last day of this month');
                
                $prevMonth = clone $firstDay;
                $prevMonth->modify('-1 month');
                $nextMonth = clone $firstDay;
                $nextMonth->modify('+1 month');

                echo '<div class="calendar-nav">';
                echo '<button onclick="location.href=\'?action=index&month=' . $prevMonth->format('m') . '&year=' . $prevMonth->format('Y') . '\'">← Anterior</button>';
                $months_es = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                $monthName = $months_es[$firstDay->format('n') - 1];
                $yearName = $firstDay->format('Y');
                
                echo '<div class="current-month">' . $monthName . ' de ' . $yearName . '</div>';
                echo '<button onclick="location.href=\'?action=index&month=' . $nextMonth->format('m') . '&year=' . $nextMonth->format('Y') . '\'">Siguiente →</button>';
                echo '</div>';

                echo '<table class="calendar">';
                echo '<thead><tr>';
                $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                foreach ($days as $day) {
                    echo '<th>' . $day . '</th>';
                }
                echo '</tr></thead>';
                echo '<tbody><tr>';

                // Get the starting day of the month (0 = Monday)
                $startDay = $firstDay->format('N') - 1;
                $day = 1;
                $cellsInMonth = (int)$lastDay->format('d');

                // Fill empty cells before month starts
                $prevLastDay = clone $firstDay;
                $prevLastDay->modify('-1 day');
                $prevCellStart = (int)$prevLastDay->format('d') - $startDay + 1;

                for ($i = 0; $i < $startDay; $i++) {
                    $prevDay = $prevCellStart + $i;
                    echo '<td class="other-month"><div class="day-number other-month">' . $prevDay . '</div></td>';
                }

                // Fill days of current month
                $cellsGenerated = $startDay;
                for ($day = 1; $day <= $cellsInMonth; $day++) {
                    $dateStr = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $isToday = $dateStr === date('Y-m-d') ? 'today' : '';
                    
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
                    if ($cellsGenerated % 7 === 0 && $day < $cellsInMonth) {
                        echo '</tr><tr>';
                    }
                }

                // Fill remaining cells with next month's days
                $remainingCells = 7 - ($cellsGenerated % 7);
                if ($remainingCells !== 7) {
                    for ($day = 1; $day <= $remainingCells; $day++) {
                        echo '<td class="other-month"><div class="day-number other-month">' . $day . '</div></td>';
                    }
                }

                echo '</tr></tbody>';
                echo '</table>';
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
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
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
