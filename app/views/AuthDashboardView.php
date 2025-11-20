<!DOCTYPE html>
<html lang="es">
<!-- Vista del panel de control (dashboard) para usuarios autenticados. -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Transfer Reservas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        /* Estilos generales del cuerpo de la página */

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
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Título de la barra de navegación */
        .navbar h2 {
            color: #333;
            font-size: 1.5em;
        }
        /* Enlaces de la barra de navegación */
        .navbar-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar-links a {
            /* Estilos para los enlaces */
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            /* Transición suave para el color */
        }

        .navbar-links a:hover {
            color: #764ba2;
        }

        /* Botón de cerrar sesión */
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white !important;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        /* Efecto hover para el botón de cerrar sesión */
        .logout-btn:hover {
            color: white !important;
            opacity: 0.9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        /* Sección de bienvenida en el dashboard */
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 40px;
            text-align: center;
        }
        /* Título de bienvenida */
        .welcome-section h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        /* Párrafo de bienvenida */
        .welcome-section p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        /* Sección de información del usuario */
        .user-info {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        /* Título de la información del usuario */
        .user-info h3 {
            color: #333;
            margin-bottom: 10px;
        }
        /* Párrafos de información del usuario */
        .user-info p {
            color: #666;
            margin: 5px 0;
        }
        /* Etiqueta de información */
        .info-label {
            font-weight: 600;
            color: #333;
        }
        /* Contenedor para los botones de acción */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        /* Estilos para los botones de acción */
        .action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1em;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border: none;
        }
        /* Efecto hover para los botones de acción */
        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }
        /* Estilos para el botón de acción secundario */
        .action-btn.secondary {
            background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%);
        }
        /* Efecto hover para el botón de acción secundario */
        .action-btn.secondary:hover {
            box-shadow: 0 12px 24px rgba(82, 196, 26, 0.4);
        }
        /* Estilos para el botón de acción de peligro */
        .action-btn.danger {
            background: linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%);
        }
        /* Efecto hover para el botón de acción de peligro */
        .action-btn.danger:hover {
            box-shadow: 0 12px 24px rgba(255, 77, 79, 0.4);
        }
        /* Estilos para el estado vacío (no hay sesión) */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        /* Párrafo en el estado vacío */
        .empty-state p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        /* Enlace de volver */
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        /* Estilos para el enlace de volver */
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        /* Efecto hover para el enlace de volver */
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación superior -->
    <div class="navbar">
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=logout" class="logout-btn">🚪 Cerrar Sesión</a>
        </div>
    </div>

    <div class="container">
        <?php if ($user): ?>
            <!-- Sección de bienvenida si el usuario está logueado -->
            <div class="welcome-section">
                <h1>¡Bienvenido! 👋</h1>
                <p><?php echo htmlspecialchars($user['user_name']); ?></p>
            </div>
            <!-- Información de la cuenta del usuario -->

            <div class="user-info">
                <h3>📋 Información de tu Cuenta</h3>
                <p><span class="info-label">Tipo de Usuario:</span> 
                    <?php
                    $userTypeLabel = [
                        'viajero' => '👤 Viajero',
                        'vehiculo' => '🚗 Conductor',
                        'hotel' => '🏨 Hotel'
                    ];
                    echo $userTypeLabel[$user['user_type']] ?? $user['user_type'];
                    ?>
                </p>
                <p><span class="info-label">ID:</span> <?php echo htmlspecialchars($user['user_id']); ?></p>
            </div>
            <!-- Botones de acción para el usuario -->

            <div class="action-buttons">
                <a href="?action=profile" class="action-btn">
                    👤 Mi Perfil
                </a>
                <?php if ($user['user_type'] !== 'hotel'): ?>
                    <a href="?action=gestion_reservas" class="action-btn">
                        📦 Gestionar Reservas
                    </a>
                    <a href="?action=index" class="action-btn secondary">
                        📅 Ver Calendario de Reservas
                    </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- Estado vacío si no hay sesión activa -->
            <div class="empty-state">
                <p>No hay sesión activa.</p>
                <a href="?action=auth" class="action-btn">Iniciar Sesión</a>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
