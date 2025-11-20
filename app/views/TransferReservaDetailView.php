<?php
// TransferReservaDetailView.php - Vista para mostrar los detalles de una reserva específica.
// Esta vista recibe la variable $reserva desde el controlador, que contiene todos los datos de la reserva.
$reserva = $reserva ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Reserva: <?= htmlspecialchars($reserva['localizador'] ?? 'N/A') ?></title>
    <style>
        /* Estilos generales para todos los elementos */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
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
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        /* Título en la barra de navegación */
        .navbar h2 { color: #333; font-size: 1.5em; }
        /* Enlaces en la barra de navegación */
        .navbar-links a { color: #667eea; text-decoration: none; font-weight: 600; }
        /* Contenedor principal de la página */
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 900px;
            padding: 40px;
            margin: 0 auto;
        }
        /* Sección del encabezado con título y botón de volver */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
        }
        /* Título principal */
        h1 { color: #333; font-size: 2em; }
        /* Botón para volver a la lista de reservas */
        .btn-back {
            background-color: #f1f3f5;
            color: #333;
            border: 1px solid #ccc;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-back:hover { background-color: #e0e0e0; }
        /* Cuadrícula para organizar las tarjetas de detalles */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        /* Tarjeta individual para una sección de detalles */
        .detail-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        /* Título dentro de una tarjeta de detalles */
        .detail-card h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        /* Elemento de detalle individual (fila de clave-valor) */
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.95em;
        }
        .detail-item:last-child { border-bottom: none; }
        .detail-item strong { color: #333; }
        /* Valor en un elemento de detalle */
        .detail-item span { color: #555; text-align: right; }
        /* Insignia para mostrar el estado de la reserva */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            color: white;
        }
        /* Colores específicos para cada estado */
        .status-badge.confirmada { background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%); }
        .status-badge.completada { background: linear-gradient(135deg, #1890ff 0%, #0050b3 100%); }
        .status-badge.pendiente { background: linear-gradient(135deg, #faad14 0%, #d48806 100%); }
        .status-badge.cancelada { background: linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%); }
    </style>
</head>
<body>
    <!-- Barra de navegación superior -->
    <div class="navbar">
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=gestion_reservas">← Volver a la gestión</a>
        </div>
    </div>

    <!-- Contenedor principal con los detalles de la reserva -->
    <div class="container">
        <div class="header-section">
            <h1>Detalles de la Reserva</h1>
            <a href="?action=gestion_reservas" class="btn-back">← Volver</a>
        </div>

        <!-- Cuadrícula que contiene las diferentes tarjetas de información -->
        <div class="details-grid">
            <!-- Tarjeta de Información General -->
            <div class="detail-card">
                <h3>ℹ️ Información General</h3>
                <div class="detail-item"><strong>Localizador:</strong> <span><?= htmlspecialchars($reserva['localizador'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Estado:</strong> <span><span class="status-badge <?= strtolower($reserva['estado'] ?? '') ?>"><?= htmlspecialchars($reserva['estado'] ?? 'N/A') ?></span></span></div>
                <div class="detail-item"><strong>Fecha Reserva:</strong> <span><?= htmlspecialchars(isset($reserva['fecha_reserva']) ? (new DateTime($reserva['fecha_reserva']))->format('d/m/Y H:i') : 'N/A') ?></span></div>
                <div class="detail-item"><strong>Última Modificación:</strong> <span><?= htmlspecialchars(isset($reserva['fecha_modificacion']) ? (new DateTime($reserva['fecha_modificacion']))->format('d/m/Y H:i') : 'N/A') ?></span></div>
            </div>

            <!-- Tarjeta con los Datos del Cliente -->
            <div class="detail-card">
                <h3>👤 Datos del Cliente</h3>
                <div class="detail-item"><strong>Nombre:</strong> <span><?= htmlspecialchars($reserva['nombre_cliente'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Email:</strong> <span><?= htmlspecialchars($reserva['email_cliente'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Pasajeros:</strong> <span><?= htmlspecialchars($reserva['num_viajeros'] ?? $reserva['num_viajeros'] ?? 'N/A') ?></span></div>
            </div>

            <!-- Tarjeta de Llegada: se muestra solo si el tipo de reserva es 'Llegada' o 'Ida y Vuelta' -->
            <?php if (in_array($reserva['id_tipo_reserva'] ?? null, ['1', '3'])): ?>
            <div class="detail-card">
                <h3>✈️ Llegada (Aeropuerto → Hotel)</h3>
                <div class="detail-item"><strong>Fecha de Llegada:</strong> <span><?= htmlspecialchars(isset($reserva['fecha_entrada']) ? (new DateTime($reserva['fecha_entrada']))->format('d/m/Y') : 'N/A') ?></span></div>
                <div class="detail-item"><strong>Hora de Llegada:</strong> <span><?= htmlspecialchars(isset($reserva['hora_entrada']) ? (new DateTime($reserva['hora_entrada']))->format('H:i') : 'N/A') ?></span></div>
                <div class="detail-item"><strong>Nº Vuelo:</strong> <span><?= htmlspecialchars($reserva['numero_vuelo_entrada'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Origen Vuelo:</strong> <span><?= htmlspecialchars($reserva['origen_vuelo_entrada'] ?? 'N/A') ?></span></div>
            </div>
            <?php endif; ?>

            <!-- Tarjeta de Salida: se muestra solo si el tipo de reserva es 'Salida' o 'Ida y Vuelta' -->
            <?php if (in_array($reserva['id_tipo_reserva'] ?? null, ['2', '3'])): ?>
            <div class="detail-card">
                <h3>🏨 Salida (Hotel → Aeropuerto)</h3>
                <div class="detail-item"><strong>Fecha de Salida:</strong> <span><?= htmlspecialchars(isset($reserva['fecha_vuelo_salida']) ? (new DateTime($reserva['fecha_vuelo_salida']))->format('d/m/Y') : 'N/A') ?></span></div>
                <div class="detail-item"><strong>Hora Partida:</strong> <span><?= htmlspecialchars(isset($reserva['hora_partida']) ? (new DateTime($reserva['hora_partida']))->format('H:i') : 'N/A') ?></span></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
