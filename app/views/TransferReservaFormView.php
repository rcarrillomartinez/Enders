<?php
require_once __DIR__ . '/../models/Auth.php';

$isEdit = isset($data['id_reserva']);
$title = $isEdit ? 'Modificar Reserva ' . htmlspecialchars($reserva['localizador']) : 'Crear Nueva Reserva';
$data = $data ?? [];
$errors = $errors ?? [];
$currentUser = Auth::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
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
        .navbar-links a { color: #667eea; text-decoration: none; font-weight: 600; }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 1200px;
            padding: 40px;
            margin: 0 auto;
        }
        h1 { color: #333; margin-bottom: 30px; text-align: center; font-size: 2em; }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        input:focus, select:focus { outline: none; border-color: #667eea; }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=gestion_reservas">← Volver a la gestión</a>
        </div>
    </div>

    <div class="container">
        <h1><?= $title ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?= htmlspecialchars(implode(', ', $errors)) ?>
            </div>
        <?php endif; ?>

        <form action="<?= $isEdit ? '?action=update' : '?action=store' ?>" method="POST">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_reserva" value="<?= htmlspecialchars($data['id_reserva']) ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="fecha_entrada">Día de Llegada:</label>
                <input type="date" name="fecha_entrada" value="<?= htmlspecialchars($data['fecha_entrada'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="hora_entrada">Hora de Llegada:</label>
                <input type="time" name="hora_entrada" value="<?= htmlspecialchars($data['hora_entrada'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="num_pasajeros">Número de Pasajeros:</label>
                <input type="number" name="num_pasajeros" value="<?= htmlspecialchars($data['num_pasajeros'] ?? 1) ?>" min="1" required>
            </div>
            <div class="form-group">
                <label for="email_cliente">Email del Cliente:</label>
                <input type="email" name="email_cliente" value="<?= htmlspecialchars($data['email_cliente'] ?? $currentUser['user_email'] ?? '') ?>" required <?= $currentUser['user_type'] !== 'admin' ? 'readonly' : '' ?>>
            </div>

            <?php if ($currentUser['user_type'] === 'admin'): ?>
                <div class="form-group">
                    <label for="estado">Estado de la Reserva:</label>
                    <select name="estado">
                        <option value="pendiente" <?= ($data['estado'] ?? '') == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmada" <?= ($data['estado'] ?? '') == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                        <option value="cancelada" <?= ($data['estado'] ?? '') == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        <option value="completada" <?= ($data['estado'] ?? '') == 'completada' ? 'selected' : '' ?>>Completada</option>
                    </select>
                </div>
            <?php endif; ?>

            <button type="submit"><?= $isEdit ? 'Guardar Cambios' : 'Crear Reserva' ?></button>
        </form>
    </div>
</body>
</html>


