<!DOCTYPE html>
<html lang="es">
<!-- Vista para la autenticación de usuarios (inicio de sesión y registro). -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($page === 'signup') ? 'Registro' : 'Inicio de Sesión'; ?> - Transfer Reservas</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Contenedor principal del formulario de autenticación */
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }

        /* Título principal de la página */
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
            font-size: 2em;
        }
        /* Subtítulo descriptivo */
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95em;
        }
        /* Contenedor para los botones de pestañas */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .tab-btn {
            /* Estilos para los botones de pestañas */
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            color: #666;
            /* Transición suave para todos los cambios */
        }

        .tab-btn.active {
            /* Estilos para el botón de pestaña activo */
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .tab-btn:hover {
            /* Efecto hover para los botones de pestañas */
            border-color: #667eea;
        }
        /* Contenedor para los botones de tipo de usuario */
        .user-type-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .user-type-btn {
            /* Estilos para los botones de selección de tipo de usuario */
            flex: 1;
            min-width: 100px;
            padding: 10px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            color: #666;
            font-size: 0.9em;
        }
        /* Estilos para el botón de tipo de usuario activo */
        .user-type-btn.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }
        /* Grupo de formulario */
        .form-group {
            margin-bottom: 20px;
        }
        /* Etiquetas de formulario */
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95em;
        }
        /* Estilos para campos de entrada y select */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s;
            font-family: inherit;
            /* Transición para el color del borde */
        }
        /* Estilos al enfocar campos de entrada y select */
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
        }
        /* Fila de formulario con diseño de cuadrícula */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            /* Espacio entre columnas */
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
            /* Efectos de transición para el botón */
        }

        button:hover {
            /* Efecto hover para el botón */
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        }

        button:active {
            /* Efecto al hacer clic en el botón */
            transform: translateY(0);
        }
        /* Estilos generales para mensajes de alerta */
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        /* Estilos para alertas de éxito */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        /* Estilos para alertas de error */
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        /* Estilos para enlaces de texto */
        .link-text {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        /* Estilos para enlaces dentro del texto */
        .link-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        /* Efecto hover para enlaces de texto */
        .link-text a:hover {
            text-decoration: underline;
        }
        /* Clase para ocultar elementos */
        .hidden {
            display: none;
        }
        /* Clase para secciones de formulario */
        .form-section {
            display: none;
        }
        /* Clase para la sección de formulario activa */
        .form-section.active {
            display: block;
        }
        /* Enlace a la página de inicio */
        .home-link {
            text-align: center;
            margin-top: 20px;
        }
        /* Estilos para el enlace de inicio */
        .home-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        /* Efecto hover para el enlace de inicio */
        .home-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Contenedor principal de la vista de autenticación -->
    <div class="container">
        <?php
        // Determina si se muestra la página de inicio de sesión o registro.
        if ($page === 'login') {
            ?>
            <h1>🔐 Inicio de Sesión</h1>
            <p class="subtitle">Accede a tu cuenta</p>

            <?php if (isset($result)): ?>
                <!-- Muestra mensajes de éxito o error después de un intento de inicio de sesión -->
                <div class="alert <?php echo $result['success'] ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo htmlspecialchars($result['message']); ?>
                </div>
                <?php if ($result['success']): ?>
                    <p style="text-align: center; color: #155724; font-weight: 600;">
                        Redirigiendo al panel de control...
                    </p>
                    <!-- Redirige al dashboard después de un inicio de sesión exitoso -->
                    <script>
                        setTimeout(() => { window.location.href = '?action=dashboard'; }, 1500);
                    </script>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Pestañas para seleccionar el tipo de usuario al iniciar sesión -->
            <div class="user-type-tabs">
                <button class="user-type-btn active" onclick="selectUserType('viajero', this)">👤 Viajero</button>
                <button class="user-type-btn" onclick="selectUserType('vehiculo', this)">🚗 Conductor</button>
                <button class="user-type-btn" onclick="selectUserType('hotel', this)">🏨 Hotel</button>
                <button class="user-type-btn" onclick="selectUserType('admin', this)">🔑 Admin</button>
            </div>

            <!-- Formulario de inicio de sesión para Viajero -->
            <form method="POST" action="?action=login" class="form-section active" id="form-viajero">
                <input type="hidden" name="user_type" value="viajero">
                <div class="form-group">
                    <label for="email-viajero">Email</label>
                    <input type="email" id="email-viajero" name="email" required placeholder="tu@email.com">
                </div>
                <div class="form-group">
                    <label for="password-viajero">Contraseña</label>
                    <input type="password" id="password-viajero" name="password" required placeholder="••••••••">
                </div>
                <button type="submit">Iniciar Sesión</button>
                <p class="link-text">¿No tienes cuenta? <a onclick="location.href='?action=signup'">Regístrate aquí</a></p>
            </form>

            <!-- Formulario de inicio de sesión para Conductor -->
            <form method="POST" action="?action=login" class="form-section" id="form-vehiculo">
                <input type="hidden" name="user_type" value="vehiculo">
                <div class="form-group">
                    <label for="email-vehiculo">Email del Conductor</label>
                    <input type="email" id="email-vehiculo" name="email" required placeholder="conductor@email.com">
                </div>
                <div class="form-group">
                    <label for="password-vehiculo">Contraseña</label>
                    <input type="password" id="password-vehiculo" name="password" required placeholder="••••••••">
                </div>
                <button type="submit">Iniciar Sesión</button>
                <p class="link-text">¿No tienes cuenta? <a onclick="location.href='?action=signup'">Regístrate aquí</a></p>
            </form>

            <!-- Formulario de inicio de sesión para Hotel -->
            <form method="POST" action="?action=login" class="form-section" id="form-hotel">
                <input type="hidden" name="user_type" value="hotel">
                <div class="form-group">
                    <label for="usuario-hotel">Usuario</label>
                    <input type="text" id="usuario-hotel" name="usuario" required placeholder="mi_usuario">
                </div>
                <div class="form-group">
                    <label for="password-hotel">Contraseña</label>
                    <input type="password" id="password-hotel" name="password" required placeholder="••••••••">
                </div>
                <button type="submit">Iniciar Sesión</button>
                <p class="link-text">¿No tienes cuenta? <a onclick="location.href='?action=signup'">Regístrate aquí</a></p>
            </form>

            <!-- Formulario de inicio de sesión para Administrador -->
            <form method="POST" action="?action=login" class="form-section" id="form-admin">
                <input type="hidden" name="user_type" value="admin">
                <div class="form-group">
                    <label for="email-admin">Email</label>
                    <input type="email" id="email-admin" name="email" required placeholder="admin@email.com">
                </div>
                <div class="form-group">
                    <label for="password-admin">Contraseña</label>
                    <input type="password" id="password-admin" name="password" required placeholder="••••••••">
                </div>
                <button type="submit">Iniciar Sesión</button>
                <p class="link-text">Acceso de administrador</p>
            </form>

            <?php
        } else {
            // Página de registro
            ?>
            <!-- Título y subtítulo para la página de registro -->
            <h1>📝 Registro</h1>
            <p class="subtitle">Crea una nueva cuenta</p>

            <?php if (isset($result)): ?>
                <!-- Muestra mensajes de éxito o error después de un intento de registro -->
                <div class="alert <?php echo $result['success'] ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo htmlspecialchars($result['message']); ?>
                </div>
            <?php endif; ?>
            <!-- Pestañas para seleccionar el tipo de usuario al registrarse -->
            <div class="user-type-tabs">
                <button class="user-type-btn active" onclick="selectUserType('viajero', this)">👤 Viajero</button>
                <button class="user-type-btn" onclick="selectUserType('vehiculo', this)">🚗 Conductor</button>
                <button class="user-type-btn" onclick="selectUserType('hotel', this)">🏨 Hotel</button>
            </div>

            <!-- Viajero Signup -->
            <!-- Formulario de registro para Viajero -->
            <form method="POST" action="?action=register" class="form-section active" id="form-viajero">
                <input type="hidden" name="user_type" value="viajero">
                <div class="form-group">
                    <label for="email-viajero">Email *</label>
                    <input type="email" id="email-viajero" name="email" required placeholder="tu@email.com">
                </div>
                <div class="form-group">
                    <label for="nombre-viajero">Nombre *</label>
                    <input type="text" id="nombre-viajero" name="nombre" required placeholder="Tu nombre">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="apellido1-viajero">Apellido 1 *</label>
                        <input type="text" id="apellido1-viajero" name="apellido1" required placeholder="Primer apellido">
                    </div>
                    <div class="form-group">
                        <label for="apellido2-viajero">Apellido 2</label>
                        <input type="text" id="apellido2-viajero" name="apellido2" placeholder="Segundo apellido">
                    </div>
                </div>
                <div class="form-group">
                    <label for="direccion-viajero">Dirección</label>
                    <input type="text" id="direccion-viajero" name="direccion" placeholder="Tu dirección">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="codigo-viajero">Código Postal</label>
                        <input type="text" id="codigo-viajero" name="codigoPostal" placeholder="28001">
                    </div>
                    <div class="form-group">
                        <label for="ciudad-viajero">Ciudad</label>
                        <input type="text" id="ciudad-viajero" name="ciudad" placeholder="Madrid">
                    </div>
                </div>
                <div class="form-group">
                    <label for="pais-viajero">País</label>
                    <input type="text" id="pais-viajero" name="pais" placeholder="España">
                </div>
                <div class="form-group">
                    <label for="password-viajero">Contraseña *</label>
                    <input type="password" id="password-viajero" name="password" required placeholder="••••••••">
                </div>
                <button type="submit">Registrarse como Viajero</button>
                <p class="link-text">¿Ya tienes cuenta? <a onclick="location.href='?action=auth'">Inicia sesión</a></p>
            </form>

            <!-- Formulario de registro para Conductor -->
            <form method="POST" action="?action=register" class="form-section" id="form-vehiculo">
                <input type="hidden" name="user_type" value="vehiculo">
                <div class="form-group">
                    <label for="email-conductor">Email del Conductor *</label>
                    <input type="email" id="email-conductor" name="email_conductor" required placeholder="conductor@email.com">
                </div>
                <div class="form-group">
                    <label for="descripcion-vehiculo">Descripción del Vehículo *</label>
                    <input type="text" id="descripcion-vehiculo" name="descripcion" required placeholder="Ej: Toyota Prius Blanco, Placa XXX">
                </div>
                <div class="form-group">
                    <label for="password-conductor">Contraseña *</label>
                    <input type="password" id="password-conductor" name="password" required placeholder="••••••••">
                </div>
                <button type="submit">Registrarse como Conductor</button>
                <p class="link-text">¿Ya tienes cuenta? <a onclick="location.href='?action=auth'">Inicia sesión</a></p>
            </form>

            <!-- Formulario de registro para Hotel -->
            <form method="POST" action="?action=register" class="form-section" id="form-hotel">
                <input type="hidden" name="user_type" value="hotel">
                <div class="form-group">
                    <label for="usuario-hotel">Usuario *</label>
                    <input type="text" id="usuario-hotel" name="usuario" required placeholder="mi_usuario">
                </div>
                <div class="form-group">
                    <label for="password-hotel">Contraseña *</label>
                    <input type="password" id="password-hotel" name="password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label for="id_zona-hotel">Zona (Opcional)</label>
                    <input type="number" id="id_zona-hotel" name="id_zona" placeholder="1">
                </div>
                <button type="submit">Registrarse como Hotel</button>
                <p class="link-text">¿Ya tienes cuenta? <a onclick="location.href='?action=auth'">Inicia sesión</a></p>
            </form>

            <?php
        }
        ?>
    </div>

    <script>
        // Función JavaScript para cambiar entre los formularios de tipo de usuario (login/signup)
        function selectUserType(userType, button) {
            // Remueve la clase 'active' de todos los botones de tipo de usuario
            document.querySelectorAll('.user-type-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            // Añade la clase 'active' al botón clickeado
            button.classList.add('active');

            // Oculta todas las secciones de formulario y muestra la correspondiente al tipo de usuario seleccionado
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById('form-' + userType).classList.add('active');
            // Actualiza el valor del campo oculto 'user_type'
            document.querySelector('input[name="user_type"]').value = userType;
        }
    </script>
</body>
</html>
