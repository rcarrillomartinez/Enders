<?php
// Este archivo PHP genera la vista del calendario de reservas.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <!-- Estilos CSS para la interfaz del calendario -->
    <style>
        * {
            margin: 0;
            padding: 0;
            /* Asegura que el padding y el borde se incluyan en el tamaño total del elemento */
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        /* Contenedor principal del calendario */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        /* Barra de navegación superior */
        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 30px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        .navbar h2 { color: #333; font-size: 1.5em; }
        /* Enlaces dentro de la barra de navegación */
        .navbar-links { display: flex; gap: 20px; align-items: center; }
        .navbar-links a { color: #667eea; text-decoration: none; font-weight: 600; transition: color 0.3s; }
        .navbar-links a:hover { color: #764ba2; }
        /* Botón de cerrar sesión */
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white !important;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .logout-btn:hover {
            /* Efecto hover para el botón de cerrar sesión */
            color: white !important;
            opacity: 0.9;
        }
        h1 {
            /* Título principal de la página */
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
        /* Navegación del calendario (anterior, siguiente, selector de vista) */
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }
        /* Contenedor para el selector de vista */
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
        /* Botones de navegación del calendario */
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
        /* Efecto hover para los botones de navegación */
        .calendar-nav button:hover {
            background-color: #764ba2;
        }
        .current-month {
            /* Texto que muestra el mes/semana/día actual */
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            flex: 1;
            text-align: center;
        }
        .calendar {
            /* Estilos de la tabla del calendario */
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        /* Encabezados de la tabla (días de la semana) */
        .calendar th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            font-size: 0.95em;
        }
        /* Celdas de la tabla (días del mes) */
        .calendar td {
            border: 1px solid #e0e0e0;
            padding: 8px;
            height: 100px;
            vertical-align: top;
            background-color: #fafafa;
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        /* Efecto hover para las celdas del calendario */
        .calendar td:hover {
            background-color: #f0f0f0;
        }
        /* Estilos para días de otros meses */
        .calendar td.other-month {
            color: #ccc;
            background-color: #f5f5f5;
        }
        /* Estilos para el día actual */
        .calendar td.today {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
        }
        .day-number {
            /* Número del día en la celda */
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 1.1em;
        }
        .day-number.other-month {
            color: #ccc;
        }
        /* Lista de reservas dentro de una celda del calendario */
        .reservations-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.8em;
        }
        .reservation-item {
            /* Elemento individual de reserva en la lista */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 6px;
            margin-bottom: 4px;
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            transition: transform 0.2s;
        }
        /* Efecto hover para los elementos de reserva */
        .reservation-item:hover {
            transform: translateX(2px);
        }
        /* Estilos para los diferentes estados de reserva */
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
        /* Mensaje de error */
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
        }
        /* Mensaje cuando no hay datos */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.1em;
        }
        .modal {
            /* Estilos para el modal de detalles de reserva */
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        /* Contenido del modal */
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            /* Centrar el modal verticalmente */
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 15px;
        }
        /* Título del modal */
        .modal-header h2 {
            color: #333;
            margin: 0;
        }
        /* Botón de cerrar modal */
        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
        }
        /* Cuerpo del modal */
        .modal-body {
            color: #555;
            line-height: 1.8;
        }
        .modal-body p {
            margin-bottom: 12px;
        }
        /* Etiquetas fuertes en el modal */
        .modal-body strong {
            color: #333;
            display: inline-block;
            min-width: 120px;
        }
        .status-badge {
            display: inline-block;
            /* Estilos para el badge de estado dentro del modal */
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
        }
        .status-badge.confirmada {
            background-color: #d4edda;
            /* Color de texto para estado confirmada */
            color: #155724;
        }
        .status-badge.completada {
            background-color: #cfe2ff;
            /* Color de texto para estado completada */
            color: #084298;
        }
        .status-badge.pendiente {
            background-color: #fff3cd;
            /* Color de texto para estado pendiente */
            color: #856404;
        }
        .status-badge.cancelada {
            background-color: #f8d7da;
            /* Color de texto para estado cancelada */
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Incluye el modelo de autenticación para verificar el estado de inicio de sesión -->
    <?php require_once __DIR__ . '/../models/Auth.php'; ?>
    <?php if (Auth::isLoggedIn()): ?>
        <div class="navbar">
            <h2>🏝️ Transfer Reservas</h2>
            <div class="navbar-links">
                <a href="?action=dashboard">Dashboard</a>
                <a href="?action=gestion_reservas">Gestionar Reservas</a>
                <a href="?action=logout" class="logout-btn">🚪 Cerrar Sesión</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <h1>📅 Calendario de Reservas</h1>

        <?php
        // Verifica si la variable $reservas ha sido proporcionada por el controlador.
        if (!isset($reservas)) {
            echo '<div class="error"><strong>Error:</strong> No se han proporcionado datos. El controlador debe suministrar <code>$reservas</code>.</div>';
        } else {
            $total = $total ?? count($reservas);
            // Inicializa $total con el número de reservas o el valor ya existente.

            // Filtrar reservas si el usuario es un viajero
            if (Auth::isLoggedIn()) {
                $currentUser = Auth::getCurrentUser();
                if ($currentUser && $currentUser['user_type'] === 'viajero' && isset($currentUser['user_email'])) {
                    $reservas = array_filter($reservas, function($reserva) use ($currentUser) {
                        return isset($reserva['email_cliente']) && $reserva['email_cliente'] === $currentUser['user_email'];
                    });
                }
            }

            // Muestra un mensaje si no hay reservas.
            if (empty($reservas)) {
                echo '<div class="no-data">No se han encontrado reservas.</div>';
            } else {
                echo '<div class="stats">Total de reservas: <strong>' . htmlspecialchars($total) . '</strong></div>';
                // Configuración de la fecha actual y la vista del calendario.
                $currentDate = new DateTime();
                if (isset($_GET['date'])) {
                    $currentDate = new DateTime($_GET['date']);
                }

                $view = $_GET['view'] ?? 'month';
                $today = new DateTime();
                // Formato de la fecha actual para comparación.
                $todayStr = $today->format('Y-m-d');

                // Organizar las reservas por fecha para el calendario
                $reservasByDate = [];
                foreach ($reservas as $reserva) {
                    // Para llegadas e ida/vuelta, usar fecha_entrada
                    if (in_array($reserva['id_tipo_reserva'], ['1', '3']) && !empty($reserva['fecha_entrada'])) {
                        $fecha = $reserva['fecha_entrada'];
                        if (!isset($reservasByDate[$fecha])) {
                            $reservasByDate[$fecha] = [];
                        }
                        $reservasByDate[$fecha][] = $reserva;
                    }
                    // Para salidas e ida/vuelta, usar fecha_vuelo_salida
                    if (in_array($reserva['id_tipo_reserva'], ['2', '3']) && !empty($reserva['fecha_vuelo_salida'])) {
                        $fecha = $reserva['fecha_vuelo_salida'];
                        if (!isset($reservasByDate[$fecha])) { $reservasByDate[$fecha] = []; }
                        $reservasByDate[$fecha][] = $reserva;
                    }
                }

                // Nombres de meses y días en español.
                $months_es = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                $days_es = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

                $prevDate = clone $currentDate;
                // Clona la fecha actual para calcular la fecha siguiente y anterior.
                $nextDate = clone $currentDate;
                $headerText = '';

                if ($view === 'month') {
                    $currentDate->modify('first day of this month');
                    $prevDate->modify('first day of this month')->modify('-1 month');
                    $nextDate->modify('first day of this month')->modify('+1 month');
                    // Formato del texto del encabezado para la vista mensual.
                    $headerText = $months_es[$currentDate->format('n') - 1] . ' de ' . $currentDate->format('Y');
                } elseif ($view === 'week') {
                    $currentDate->modify('monday this week');
                    $prevDate->modify('monday this week')->modify('-1 week');
                    $nextDate->modify('monday this week')->modify('+1 week');
                    $endOfWeek = (clone $currentDate)->modify('+6 days');
                    // Formato del texto del encabezado para la vista semanal.
                    $headerText = 'Semana del ' . $currentDate->format('d/m/Y') . ' al ' . $endOfWeek->format('d/m/Y');
                } else {
                    $prevDate->modify('-1 day');
                    $nextDate->modify('+1 day');
                    // Formato del texto del encabezado para la vista diaria.
                    $headerText = $days_es[$currentDate->format('N') - 1] . ', ' . $currentDate->format('d') . ' de ' . $months_es[$currentDate->format('n') - 1] . ' de ' . $currentDate->format('Y');
                }
                // Renderiza la barra de navegación del calendario.
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

                // Renderiza el calendario según la vista seleccionada.
                if ($view === 'month') {
                    // Crea el calendario mensual
                    $firstDayOfMonth = (clone $currentDate)->modify('first day of this month');
                    // Obtiene el primer y último día del mes actual.
                    $lastDayOfMonth = (clone $currentDate)->modify('last day of this month');
                    
                    echo '<table class="calendar">';
                    echo '<thead><tr>';
                    foreach ($days_es as $dayName) {
                        echo '<th>' . $dayName . '</th>';
                    }
                    echo '</tr></thead>';
                    echo '<tbody><tr>';
                    
                    // Calcula el día de la semana del primer día del mes.
                    $startDayOfWeek = $firstDayOfMonth->format('N') - 1;
                    $daysInMonth = (int)$lastDayOfMonth->format('d');

                    $prevMonthLastDay = (clone $firstDayOfMonth)->modify('-1 day');
                    // Calcula el inicio de los días del mes anterior que se muestran.
                    $prevMonthCellStart = (int)$prevMonthLastDay->format('d') - $startDayOfWeek + 1;

                    for ($i = 0; $i < $startDayOfWeek; $i++) {
                        echo '<td class="other-month"><div class="day-number other-month">' . ($prevMonthCellStart + $i) . '</div></td>';
                    }

                    $cellsGenerated = $startDayOfWeek;
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        // Formatea la fecha para la celda actual.
                        $dateStr = $currentDate->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $isToday = $dateStr === $todayStr ? 'today' : '';
                        
                        echo '<td class="' . $isToday . '">';
                        echo '<div class="day-number">' . $day . '</div>';

                        if (isset($reservasByDate[$dateStr])) {
                            echo '<ul class="reservations-list">';
                            foreach ($reservasByDate[$dateStr] as $reserva) {
                                // Muestra las reservas para el día actual.
                                $status = $reserva['estado'] ?? 'pendiente';
                                $localizador = $reserva['localizador'] ?? 'N/A';
                                echo '<li class="reservation-item ' . htmlspecialchars($status) . '" onclick="showReservation(event)" data-reserva=\'' . htmlspecialchars(json_encode($reserva)) . '\'>';
                                echo htmlspecialchars($localizador);
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        echo '</td>';
                        
                        // Inicia una nueva fila cada 7 celdas.
                        $cellsGenerated++;
                        if ($cellsGenerated % 7 === 0 && $day < $daysInMonth) {
                            echo '</tr><tr>';
                        }
                    }

                    $remainingCells = 7 - ($cellsGenerated % 7);
                    // Rellena las celdas restantes con días del mes siguiente.
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
                    // Itera para mostrar los días de la semana en el encabezado.
                    $dayIterator = clone $currentDate;
                    for ($i = 0; $i < 7; $i++) {
                        echo '<th>' . $days_es[$dayIterator->format('N') - 1] . ' ' . $dayIterator->format('d/m') . '</th>';
                        $dayIterator->modify('+1 day');
                    }
                    echo '</tr></thead>';
                    echo '<tbody><tr>';
                    $dayIterator = clone $currentDate;
                    // Itera para mostrar las celdas de los días de la semana.
                    for ($i = 0; $i < 7; $i++) {
                        $dateStr = $dayIterator->format('Y-m-d');
                        $isToday = $dateStr === $todayStr ? 'today' : '';
                        echo '<td class="' . $isToday . '">';
                        // Muestra el número del día.
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
                    // Encabezado para la vista diaria.
                    echo '<thead><tr><th>' . $headerText . '</th></tr></thead>';
                    echo '<tbody><tr>';
                    $dateStr = $currentDate->format('Y-m-d');
                    $isToday = $dateStr === $todayStr ? 'today' : '';
                    // Celda para el día actual en la vista diaria.
                    echo '<td class="' . $isToday . '" style="height: 60vh;">';
                    if (isset($reservasByDate[$dateStr])) {
                        echo '<ul class="reservations-list">';
                        foreach ($reservasByDate[$dateStr] as $reserva) {
                            $status = $reserva['estado'] ?? 'pendiente';
                            $localizador = $reserva['localizador'] ?? 'N/A';
                            $hora = isset($reserva['hora_partida']) ? (new DateTime($reserva['hora_partida']))->format('H:i') : '';
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

    <!-- Modal para mostrar los detalles de una reserva -->
    <div id="reservaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalles de Reserva</h2>
                <!-- Botón para cerrar el modal -->
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Detalles de la reserva se ponen aquí -->
            </div>
        </div>
    </div>

    <script>
        // Función para cambiar la vista del calendario (mes, semana, día)
        function changeView(selector) {
            const newView = selector.value;
            const currentDate = '<?php echo $currentDate->format('Y-m-d'); ?>';
            location.href = `?action=index&view=${newView}&date=${currentDate}`;
        }

        // Función para mostrar los detalles de una reserva en el modal
        function showReservation(event) {
            // Encuentra el elemento de reserva más cercano al evento click
            const item = event.target.closest('.reservation-item');
            if (!item) return;
            
            // Parsea los datos de la reserva desde el atributo 'data-reserva'
            const reservaJson = item.getAttribute('data-reserva');
            const reserva = JSON.parse(reservaJson);

            function formatField(label, value) {
                return `<p><strong>${label}:</strong> <span>${value || 'N/A'}</span></p>`;
            }

            function formatDate(dateStr) {
                if (!dateStr) return 'N/A';
                const date = new Date(dateStr);
                return date.toLocaleDateString('es-ES');
            }

            function formatTime(timeStr) {
                if (!timeStr) return 'N/A';
                return timeStr.substring(0, 5);
            }
            
            let html = '';

            // Información General
            html += '<h3>ℹ️ Información General</h3>';
            html += formatField('Localizador', reserva.localizador);
            html += `<p><strong>Estado:</strong> <span class="status-badge ${reserva.estado}">${reserva.estado}</span></p>`;
            html += formatField('Pasajeros', reserva.num_viajeros);

            // Datos del Cliente
            html += '<h3>👤 Datos del Cliente</h3>';
            html += formatField('Nombre', reserva.nombre_cliente);
            html += formatField('Email', reserva.email_cliente);

            // Detalles de Llegada
            if (reserva.id_tipo_reserva == '1' || reserva.id_tipo_reserva == '3') {
                html += '<h3>✈️ Llegada (Aeropuerto → Hotel)</h3>';
                html += formatField('Fecha Llegada', formatDate(reserva.fecha_entrada));
                html += formatField('Hora Llegada', formatTime(reserva.hora_entrada));
                html += formatField('Nº Vuelo', reserva.numero_vuelo_entrada);
            }

            // Detalles de Salida
            if (reserva.id_tipo_reserva == '2' || reserva.id_tipo_reserva == '3') {
                html += '<h3>🏨 Salida (Hotel → Aeropuerto)</h3>';
                html += formatField('Fecha Salida', formatDate(reserva.fecha_vuelo_salida));
                html += formatField('Hora Partida', formatTime(reserva.hora_partida));
            }

            // Inserta el HTML generado en el cuerpo del modal y lo muestra
            document.getElementById('modalBody').innerHTML = html;
            document.getElementById('reservaModal').style.display = 'block';
        }

        // Función para cerrar el modal
        function closeModal() {
            document.getElementById('reservaModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('reservaModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
        // Cierra el modal si se hace clic fuera de él
    </script>
</body>
</html>
