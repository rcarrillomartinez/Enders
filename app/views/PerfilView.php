<?php
// app/views/PerfilView.php
// Asume $user (sesión), $data (datos actuales), $errors, $message

$currentData = $data ?? [];
$isViajero = $user['user_type'] === 'viajero';
$identifierLabel = $isViajero || $user['user_type'] === 'admin' ? 'Email' : 'Usuario';
$identifierValue = $currentData['email'] ?? $currentData['usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    </head>
<body>
    <div class="container">
        <h1>👤 Mi Perfil</h1>
        <h2>Datos de <?= ucfirst($user['user_type']) ?></h2>

        <?php if (!empty($errors)): ?><div style="color: red;"><?= htmlspecialchars(implode(', ', $errors)) ?></div><?php endif; ?>
        <?php if ($message): ?><div style="color: green;"><?= htmlspecialchars($message) ?></div><?php endif; ?>

        <form action="?action=profile_update" method="POST">
            
            <label for="identifier"><?= $identifierLabel ?>:</label>
            <input type="text" name="email" id="identifier" value="<?= htmlspecialchars($identifierValue) ?>" required>
            
            <?php if (isset($currentData['nombre']) || $user['user_type'] === 'viajero'): // Mostrar nombre si existe o si es viajero/admin ?>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($currentData['nombre'] ?? '') ?>">
            <?php endif; ?>
            
            <hr>
            
            <h3>Cambiar Contraseña (Dejar en blanco si no quieres cambiar)</h3>
            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" name="new_password" id="new_password">
            
            <label for="confirm_password">Confirmar Nueva Contraseña:</label>
            <input type="password" name="confirm_password" id="confirm_password">
            
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
