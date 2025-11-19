<?php
$reserva = $reserva ?? [];
// Vista para mostrar los detalles de una reserva específica.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Reserva: <?= htmlspecialchars($reserva['localizador'] ?? 'N/A') ?></title>
    <!-- Estilos CSS para la página de detalles de reserva -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Fondo degradado para la página */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
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
        .navbar h2 { color: #333; font-size: 1.5em; }
        /* Enlace de navegación */
        .navbar-links a { color: #667eea; text-decoration: none; font-weight: 600; }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 900px;
            padding: 40px;
            margin: 0 auto;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
        }
        /* Título principal de la página */
        h1 { color: #333; font-size: 2em; }
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
        /* Efecto hover para el botón de volver */
        .btn-back:hover { background-color: #e0e0e0; }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .detail-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        /* Título de las tarjetas de detalle */
        .detail-card h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.95em;
        }
        /* Eliminar borde inferior del último elemento de detalle */
        .detail-item:last-child { border-bottom: none; }
        /* Estilos para el texto fuerte y el valor del detalle */
        .detail-item strong { color: #333; }
        .detail-item span { color: #555; text-align: right; }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            color: white;
        }
        /* Estilos para los diferentes estados de la reserva */
        .status-badge.confirmada { background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%); }
        .status-badge.completada { background: linear-gradient(135deg, #1890ff 0%, #0050b3 100%); }
        .status-badge.pendiente { background: linear-gradient(135deg, #faad14 0%, #d48806 100%); }
        .status-badge.cancelada { background: linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%); }
    </style>
</head>
<body>
    <div class="navbar">
        <!-- Título de la barra de navegación -->
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=gestion_reservas">← Volver a la gestión</a>
        </div>
    </div>

    <div class="container">
        <div class="header-section">
            <!-- Título de la sección de detalles -->
            <h1>Detalles de la Reserva</h1>
            <a href="?action=gestion_reservas" class="btn-back">← Volver</a>
        </div>

        <div class="details-grid">
            <div class="detail-card">
                <!-- Información general de la reserva -->
                <h3>ℹ️ Información General</h3>
                <div class="detail-item"><strong>Localizador:</strong> <span><?= htmlspecialchars($reserva['localizador'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Estado:</strong> <span><span class="status-badge <?= strtolower($reserva['estado'] ?? '') ?>"><?= htmlspecialchars($reserva['estado'] ?? 'N/A') ?></span></span></div>
                <div class="detail-item"><strong>Fecha Reserva:</strong> <span><?= htmlspecialchars(isset($reserva['fecha_reserva']) ? (new DateTime($reserva['fecha_reserva']))->format('d/m/Y H:i') : 'N/A') ?></span></div>
                <div class="detail-item"><strong>Última Modificación:</strong> <span><?= htmlspecialchars(isset($reserva['fecha_modificacion']) ? (new DateTime($reserva['fecha_modificacion']))->format('d/m/Y H:i') : 'N/A') ?></span></div>
            </div>

            <div class="detail-card">
                <!-- Datos del cliente -->
                <h3>👤 Datos del Cliente</h3>
                <div class="detail-item"><strong>Nombre:</strong> <span><?= htmlspecialchars($reserva['nombre_cliente'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Email:</strong> <span><?= htmlspecialchars($reserva['email_cliente'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Pasajeros:</strong> <span><?= htmlspecialchars($reserva['num_viajeros'] ?? $reserva['num_viajeros'] ?? 'N/A') ?></span></div>
            </div>

            <?php if (!empty($reserva['numero_vuelo_entrada'])): ?>
            <!-- Detalles de llegada (Aeropuerto -> Hotel) si existen -->
            <div class="detail-card">
                <h3>✈️ Llegada (Aeropuerto → Hotel)</h3>
                <div class="detail-item"><strong>Fecha de Llegada:</strong> <span><?= htmlspecialchars(isset($reserva['fecha_entrada']) ? (new DateTime($reserva['fecha_entrada']))->format('d/m/Y') : 'N/A') ?></span></div>
                <div class="detail-item"><strong>Hora de Llegada:</strong> <span><?= htmlspecialchars(isset($reserva['hora_entrada']) ? (new DateTime($reserva['hora_entrada']))->format('H:i') : 'N/A') ?></span></div>
                <div class="detail-item"><strong>Nº Vuelo:</strong> <span><?= htmlspecialchars($reserva['numero_vuelo_entrada'] ?? 'N/A') ?></span></div>
                <div class="detail-item"><strong>Origen Vuelo:</strong> <span><?= htmlspecialchars($reserva['origen_vuelo_entrada'] ?? 'N/A') ?></span></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($reserva['fecha_vuelo_salida'])): ?>
            <!-- Detalles de salida (Hotel -> Aeropuerto) si existen -->
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
