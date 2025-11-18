<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas - Transfer Reservas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .navbar h2 { color: #333; font-size: 1.5em; }
        .navbar-links { display: flex; gap: 20px; align-items: center; }
        .navbar-links a { color: #667eea; text-decoration: none; font-weight: 600; transition: color 0.3s; }
        .navbar-links a:hover { color: #764ba2; }
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white !important;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 1200px;
            padding: 40px;
            margin: 0 auto;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h1 { color: #333; font-size: 2em; }
        .btn-primary {
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        }
        .table-wrapper { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        tr:hover { background-color: #f1f3f5; }
        .actions a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-right: 15px;
        }
        .actions a.view { color: #3b82f6; } 
        .actions a.edit { color: #facc15; } 
        .actions a.delete { color: #ff6b6b; }
        .actions a:hover {
            opacity: 0.8;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
    </style>

</head>
<body>
    <div class="navbar">
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=dashboard">Dashboard</a>
            <a href="?action=index">Calendario</a>
            <a href="?action=logout" class="logout-btn">🚪 Cerrar Sesión</a>
        </div>
    </div>

    <div class="container">
        <div class="header-section">
            <h1>📦 Gestión de Reservas</h1>
            <a href="?action=create" class="btn-primary">➕ Crear Nueva Reserva</a>
        </div>

        <?php
            $status = $_GET['status'] ?? '';
            if ($status === 'created') echo '<div class="alert alert-success">Reserva creada correctamente.</div>';
            if ($status === 'updated') echo '<div class="alert alert-success">Reserva actualizada correctamente.</div>';
            if ($status === 'deleted') echo '<div class="alert alert-success">Reserva eliminada correctamente.</div>';
            if ($status === 'notfound') echo '<div class="alert alert-error">Reserva no encontrada.</div>';
        ?>

        <?php if (empty($reservas)): ?>
            <div class="empty-state">
                <p>No se han encontrado reservas. ¡Crea la primera!</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Localizador</th>
                            <th>Fecha Entrada</th>
                            <th>Hora</th>
                            <th>Pasajeros</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['localizador'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($reserva['fecha_entrada'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($reserva['hora_entrada'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($reserva['num_pasajeros'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($reserva['email_cliente'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($reserva['estado'] ?? 'N/A') ?></td>
                                <td class="actions">
                                    <a href="?action=show&id=<?= $reserva['id_reserva'] ?>" class="view" title="Ver Detalles">👁️</a>
                                    <a href="?action=edit&id=<?= $reserva['id_reserva'] ?>"class="edit" title="Editar">✏️</a>
                                    <a href="?action=delete&id=<?= $reserva['id_reserva'] ?>" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar esta reserva?');">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
