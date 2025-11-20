<?php
// PerfilView.php - Vista para mostrar y actualizar el perfil del usuario.
// Esta vista recibe las siguientes variables desde el controlador:
// - $user: Array con la información del usuario de la sesión (tipo, id, nombre).
// - $data: Array con los datos completos del perfil obtenidos de la base de datos.
// - $errors: Array con mensajes de error, si los hay.
// - $message: Mensaje de éxito, si lo hay.

// Prepara los datos para mostrarlos en el formulario.
$currentData = $data ?? [];
$isViajero = $user['user_type'] === 'viajero';
// Determina la etiqueta del campo identificador (Email o Usuario) según el tipo de usuario.
$identifierLabel = $isViajero || $user['user_type'] === 'admin' ? 'Email' : 'Usuario';
$identifierValue = $currentData['email'] ?? $currentData['usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Transfer Reservas</title>
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
            max-width: 800px;
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

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            padding: 40px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
            font-size: 2em;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95em;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

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

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        hr {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación superior con enlaces al dashboard y para cerrar sesión. -->
    <div class="navbar">
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=dashboard"> Dashboard</a>
            <a href="?action=logout" class="logout-btn">🚪 Cerrar Sesión</a>
        </div>
    </div>

    <!-- Contenedor principal del formulario de perfil. -->
    <div class="container">
        <h1>👤 Mi Perfil</h1>
        <p class="subtitle">Actualiza los datos de tu cuenta de <?= ucfirst($user['user_type']) ?></p>

        <!-- Muestra mensajes de error si existen. -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?= htmlspecialchars(implode(', ', $errors)) ?></div>
        <?php endif; ?>
        <!-- Muestra un mensaje de éxito si existe. -->
        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Formulario para actualizar el perfil. Envía los datos a la acción 'profile_update'. -->
        <form action="?action=profile_update" method="POST">
            <!-- Campo para el identificador principal (Email o Usuario). -->
            <div class="form-group">
                <label for="identifier"><?= $identifierLabel ?>:</label>
                <input type="text" name="email" id="identifier" value="<?= htmlspecialchars($identifierValue) ?>" required>
            </div>

            <!-- Campo para el nombre, se muestra solo si el tipo de usuario lo requiere. -->
            <?php if (isset($currentData['nombre']) || $user['user_type'] === 'viajero' || $user['user_type'] === 'admin'): ?>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($currentData['nombre'] ?? '') ?>">
            </div>
            <?php endif; ?>

            <!-- Separador para la sección de cambio de contraseña. -->
            <hr>

            <p class="subtitle" style="margin-bottom: 20px;">Cambiar Contraseña (dejar en blanco para no modificar)</p>

            <div class="form-group">
                <label for="new_password">Nueva Contraseña:</label>
                <input type="password" name="new_password" id="new_password" placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••">
            </div>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
