<!DOCTYPE html>
<html lang="es">
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .navbar h2 {
            color: #333;
            font-size: 1.5em;
        }

        .navbar-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .navbar-links a:hover {
            color: #764ba2;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white !important;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

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

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 40px;
            text-align: center;
        }

        .welcome-section h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .welcome-section p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .user-info {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .user-info h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .user-info p {
            color: #666;
            margin: 5px 0;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

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

        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }

        .action-btn.secondary {
            background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%);
        }

        .action-btn.secondary:hover {
            box-shadow: 0 12px 24px rgba(82, 196, 26, 0.4);
        }

        .action-btn.danger {
            background: linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%);
        }

        .action-btn.danger:hover {
            box-shadow: 0 12px 24px rgba(255, 77, 79, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=logout" class="logout-btn">🚪 Cerrar Sesión</a>
        </div>
    </div>

    <div class="container">
        <?php if ($user): ?>
            <div class="welcome-section">
                <h1>¡Bienvenido! 👋</h1>
                <p><?php echo htmlspecialchars($user['user_name']); ?></p>
            </div>

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

            <div class="action-buttons">
                <a href="?action=profile" class="action-btn">
                    👤 Mi Perfil
                </a>
                <a href="?action=gestion_reservas" class="action-btn">
                    📦 Gestionar Reservas
                </a>
                <a href="?action=index" class="action-btn secondary">
                    📅 Ver Calendario de Reservas
                </a>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <p>No hay sesión activa.</p>
                <a href="?action=auth" class="action-btn">Iniciar Sesión</a>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
