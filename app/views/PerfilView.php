<h2>Perfil de Usuario</h2>

<?php if(!empty($message)) echo "<p style='color: green;'>$message</p>"; ?>
<?php if(!empty($errors)) foreach($errors as $error) echo "<p style='color: red;'>$error</p>"; ?>

<form method="post" action="?action=updatePerfil">
    <fieldset>
        <legend>Datos básicos</legend>
        <label>Nombre:
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($user['nombre'] ?? ''); ?>" readonly>
        </label><br>

        <label>Primer Apellido:
            <input type="text" name="apellido1" value="<?php echo htmlspecialchars($user['apellido1'] ?? ''); ?>" readonly>
        </label><br>

        <label>Segundo Apellido:
            <input type="text" name="apellido2" value="<?php echo htmlspecialchars($user['apellido2'] ?? ''); ?>" readonly>
        </label><br>

        <label>Email:
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </label>
    </fieldset>

    <fieldset>
        <legend>Datos adicionales</legend>
        <label>Dirección:
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($data['direccion'] ?? ''); ?>">
        </label><br>

        <label>Código Postal:
            <input type="text" name="codigoPostal" value="<?php echo htmlspecialchars($data['codigoPostal'] ?? ''); ?>">
        </label><br>

        <label>Ciudad:
            <input type="text" name="ciudad" value="<?php echo htmlspecialchars($data['ciudad'] ?? ''); ?>">
        </label><br>

        <label>País:
            <input type="text" name="pais" value="<?php echo htmlspecialchars($data['pais'] ?? ''); ?>">
        </label>
    </fieldset>

    <fieldset>
        <legend>Cambiar contraseña</legend>
        <label>Contraseña:
            <input type="password" name="password">
        </label><br>

        <label>Confirmar Contraseña:
            <input type="password" name="password_confirm">
        </label>
    </fieldset>

    <button type="submit">Actualizar Perfil</button>
</form>
<form action="?action=dashboard" method="get" style="margin-top: 20px;">
    <button type="submit">Volver</button>
</form>
