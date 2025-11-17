<?php
// app/views/AuthView.php

class AuthView {

    /**
     * Punto de entrada principal para renderizar la página de Login.
     * @param array|null $result Array con el resultado de la operación).
     * @param array $formData Datos previamente enviados por el usuario para 'pegar' en los campos.
     */
    public static function renderLoginForm(?array $result = null, array $formData = []): void {
        self::renderAuthPage('login', $result, $formData);
    }

    /**
     * Punto de entrada principal para renderizar la página de Registro (Signup).
     * @param array|null $result Array con el resultado de la operación.
     * @param array $formData Datos previamente enviados por el usuario.
     */
    public static function renderSignupForm(?array $result = null, array $formData = []): void {
        self::renderAuthPage('signup', $result, $formData);
    }
    
    /**
     * Método privado para obtener un valor pre-llenado de los datos del formulario.
     * @param array $formData Datos del POST
     * @param string $key Clave del campo
     * @return string Valor escapado
     */
    private static function getFormData(array $formData, string $key): string {
        return htmlspecialchars($formData[$key] ?? '');
    }

    /**
     * Renderiza el contenido completo de la página de autenticación (Login o Registro).
     *
     * @param string $page 'login' o 'signup'.
     * @param array|null $result Array con el resultado de la operación ).
     * @param array $formData Datos previamente enviados por el usuario para 'pegar' en los campos.
     */
    private static function renderAuthPage(string $page, ?array $result = null, array $formData = []): void {
        
        // Función para simplificar la obtención de datos
        $get = function (string $key) use ($formData) {
            return self::getFormData($formData, $key);
        };
        
        // Inicio del HTML y estilos CSS

        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo ($page === 'signup') ? 'Registro' : 'Inicio de Sesión'; ?> - Transfer Reservas</title>
            <style>
                /* [SECCIÓN DE ESTILOS OMITIDA POR BREVEDAD - ES EL MISMO CSS] */
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
                .container { background: white; border-radius: 12px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2); width: 100%; max-width: 500px; padding: 40px; }
                h1 { color: #333; margin-bottom: 10px; text-align: center; font-size: 2em; }
                .subtitle { text-align: center; color: #666; margin-bottom: 30px; font-size: 0.95em; }
                .user-type-tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
                .user-type-btn { flex: 1; min-width: 100px; padding: 10px; border: 2px solid #e0e0e0; background: white; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s; color: #666; font-size: 0.9em; }
                .user-type-btn.active { border-color: #667eea; background: #667eea; color: white; }
                .form-group { margin-bottom: 20px; }
                label { display: block; margin-bottom: 8px; color: #333; font-weight: 600; font-size: 0.95em; }
                input[type="text"], input[type="email"], input[type="password"], input[type="number"], select { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1em; transition: border-color 0.3s; font-family: inherit; }
                input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="number"]:focus, select:focus { outline: none; border-color: #667eea; }
                .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
                button { width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-size: 1em; font-weight: 600; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
                button:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4); }
                button:active { transform: translateY(0); }
                .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
                .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
                .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
                .link-text { text-align: center; margin-top: 20px; color: #666; }
                .link-text a { color: #667eea; text-decoration: none; font-weight: 600; cursor: pointer; }
                .link-text a:hover { text-decoration: underline; }
                .hidden { display: none; }
                .form-section { display: none; }
                .form-section.active { display: block; }
                .home-link { text-align: center; margin-top: 20px; }
                .home-link a { color: #667eea; text-decoration: none; font-weight: 600; }
                .home-link a:hover { text-decoration: underline; }
            </style>
        </head>
        <body>
            <div class="container">
        <?php

        // Lógica de la vista 
        
        if ($page === 'login') {
            // Inicio de sesión 
            ?>
                <h1>🔐 Inicio de Sesión</h1>
                <p class="subtitle">Accede a tu cuenta</p>

                <?php if (isset($result) && is_array($result) && (isset($result['message']) || isset($result['success']))): ?>
                    <div class="alert <?php echo ($result['success'] ?? false) ? 'alert-success' : 'alert-error'; ?>">
                        <?php echo htmlspecialchars($result['message'] ?? 'Error desconocido'); ?>
                    </div>
                    <?php if ($result['success'] ?? false): ?>
                        <p style="text-align: center; color: #156724; font-weight: 600;">
                            Redirigiendo al calendario... <a href="?action=index">Ir al calendario</a>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="user-type-tabs">
                    <button class="user-type-btn active" onclick="selectUserType('viajero', this)">👤 Viajero</button>
                    <button class="user-type-btn" onclick="selectUserType('vehiculo', this)">🚗 Conductor</button>
                    <button class="user-type-btn" onclick="selectUserType('hotel', this)">🏨 Hotel</button>
                    <button class="user-type-btn" onclick="selectUserType('admin', this)">🔑 Admin</button>
                </div>

                <form method="POST" action="?action=login" class="form-section active" id="form-viajero">
                    <input type="hidden" name="user_type" value="viajero">
                    <div class="form-group">
                        <label for="email-viajero">Email</label>
                        <input type="email" id="email-viajero" name="identifier" required placeholder="tu@email.com" value="<?= $get('identifier') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password-viajero">Contraseña</label>
                        <input type="password" id="password-viajero" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit">Iniciar Sesión</button>
                    <p class="link-text">¿No tienes cuenta? <a onclick="location.href='?action=signup'">Regístrate aquí</a></p>
                </form>

                <form method="POST" action="?action=login" class="form-section" id="form-vehiculo">
                    <input type="hidden" name="user_type" value="vehiculo">
                    <div class="form-group">
                        <label for="email-vehiculo">Email del Conductor</label>
                        <input type="email" id="email-vehiculo" name="identifier" required placeholder="conductor@email.com" value="<?= $get('identifier') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password-vehiculo">Contraseña</label>
                        <input type="password" id="password-vehiculo" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit">Iniciar Sesión</button>
                    <p class="link-text">¿No tienes cuenta? <a onclick="location.href='?action=signup'">Regístrate aquí</a></p>
                </form>

                <form method="POST" action="?action=login" class="form-section" id="form-hotel">
                    <input type="hidden" name="user_type" value="hotel">
                    <div class="form-group">
                        <label for="usuario-hotel">Usuario</label>
                        <input type="text" id="usuario-hotel" name="identifier" required placeholder="mi_usuario" value="<?= $get('identifier') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password-hotel">Contraseña</label>
                        <input type="password" id="password-hotel" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit">Iniciar Sesión</button>
                    <p class="link-text">¿No tienes cuenta? <a onclick="location.href='?action=signup'">Regístrate aquí</a></p>
                </form>

                <form method="POST" action="?action=login" class="form-section" id="form-admin">
                    <input type="hidden" name="user_type" value="admin">
                    <div class="form-group">
                        <label for="email-admin">Email</label>
                        <input type="email" id="email-admin" name="identifier" required placeholder="admin@email.com" value="<?= $get('identifier') ?>">
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
            // Registro
            ?>
                <h1>📝 Registro</h1>
                <p class="subtitle">Crea una nueva cuenta</p>

                <?php if (isset($result) && is_array($result) && (isset($result['message']) || isset($result['success']))): ?>
                    <div class="alert <?php echo ($result['success'] ?? false) ? 'alert-success' : 'alert-error'; ?>">
                        <?php echo htmlspecialchars($result['message'] ?? 'Error desconocido'); ?>
                    </div>
                <?php endif; ?>

                <div class="user-type-tabs">
                    <button class="user-type-btn active" onclick="selectUserType('viajero', this)">👤 Viajero</button>
                    <button class="user-type-btn" onclick="selectUserType('vehiculo', this)">🚗 Conductor</button>
                    <button class="user-type-btn" onclick="selectUserType('hotel', this)">🏨 Hotel</button>
                </div>

                <form method="POST" action="?action=register" class="form-section active" id="form-viajero">
                    <input type="hidden" name="user_type" value="viajero">
                    <div class="form-group">
                        <label for="email-viajero">Email *</label>
                        <input type="email" id="email-viajero" name="email" required placeholder="tu@email.com" value="<?= $get('email') ?>">
                    </div>
                    <div class="form-group">
                        <label for="nombre-viajero">Nombre *</label>
                        <input type="text" id="nombre-viajero" name="nombre" required placeholder="Tu nombre" value="<?= $get('nombre') ?>">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="apellido1-viajero">Apellido 1 *</label>
                            <input type="text" id="apellido1-viajero" name="apellido1" required placeholder="Primer apellido" value="<?= $get('apellido1') ?>">
                        </div>
                        <div class="form-group">
                            <label for="apellido2-viajero">Apellido 2</label>
                            <input type="text" id="apellido2-viajero" name="apellido2" placeholder="Segundo apellido" value="<?= $get('apellido2') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion-viajero">Dirección</label>
                        <input type="text" id="direccion-viajero" name="direccion" placeholder="Tu dirección" value="<?= $get('direccion') ?>">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo-viajero">Código Postal</label>
                            <input type="text" id="codigo-viajero" name="codigoPostal" placeholder="28001" value="<?= $get('codigoPostal') ?>">
                        </div>
                        <div class="form-group">
                            <label for="ciudad-viajero">Ciudad</label>
                            <input type="text" id="ciudad-viajero" name="ciudad" placeholder="Madrid" value="<?= $get('ciudad') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pais-viajero">País</label>
                        <input type="text" id="pais-viajero" name="pais" placeholder="España" value="<?= $get('pais') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password-viajero">Contraseña *</label>
                        <input type="password" id="password-viajero" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit">Registrarse como Viajero</button>
                    <p class="link-text">¿Ya tienes cuenta? <a onclick="location.href='?action=login'">Inicia sesión</a></p>
                </form>

                <form method="POST" action="?action=register" class="form-section" id="form-vehiculo">
                    <input type="hidden" name="user_type" value="vehiculo">
                    <div class="form-group">
                        <label for="email-conductor">Email del Conductor *</label>
                        <input type="email" id="email-conductor" name="email_conductor" required placeholder="conductor@email.com" value="<?= $get('email_conductor') ?>">
                    </div>
                    <div class="form-group">
                        <label for="descripcion-vehiculo">Descripción del Vehículo *</label>
                        <input type="text" id="descripcion-vehiculo" name="descripcion" required placeholder="Ej: Toyota Prius Blanco, Placa XXX" value="<?= $get('descripcion') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password-conductor">Contraseña *</label>
                        <input type="password" id="password-conductor" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit">Registrarse como Conductor</button>
                    <p class="link-text">¿Ya tienes cuenta? <a onclick="location.href='?action=login'">Inicia sesión</a></p>
                </form>

                <form method="POST" action="?action=register" class="form-section" id="form-hotel">
                    <input type="hidden" name="user_type" value="hotel">
                    <div class="form-group">
                        <label for="usuario-hotel">Usuario *</label>
                        <input type="text" id="usuario-hotel" name="usuario" required placeholder="mi_usuario" value="<?= $get('usuario') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password-hotel">Contraseña *</label>
                        <input type="password" id="password-hotel" name="password" required placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label for="id_zona-hotel">Zona (Opcional)</label>
                        <input type="number" id="id_zona-hotel" name="id_zona" placeholder="1" value="<?= $get('id_zona') ?>">
                    </div>
                    <button type="submit">Registrarse como Hotel</button>
                    <p class="link-text">¿Ya tienes cuenta? <a onclick="location.href='?action=login'">Inicia sesión</a></p>
                </form>

            <?php
        }
        ?>
            </div>

            <script>
                // Función para manejar la activación de pestañas y formularios
                function selectUserType(userType, button) {
                    document.querySelectorAll('.user-type-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    button.classList.add('active');

                    document.querySelectorAll('.form-section').forEach(section => {
                        section.classList.remove('active');
                    });
                    
                    let formId = 'form-' + userType;
                    let targetForm = document.getElementById(formId);

                    if (targetForm) {
                        targetForm.classList.add('active');
                    }
                    
                    // Ajustar el campo hidden del formulario activo
                    document.querySelectorAll('.form-section.active input[name="user_type"]').forEach(input => {
                        input.value = userType;
                    });
                }

                // Inicializar la vista cargando el formulario correcto al cargar la página
                document.addEventListener('DOMContentLoaded', () => {
                    // Si hay datos pre-llenados (ej. por error), activa la pestaña del usuario que intentó loggear/registrar
                    const initialUserType = "<?= $get('user_type') ?: 'viajero' ?>";
                    const initialButton = document.querySelector(`.user-type-btn[onclick*="'${initialUserType}'"]`);
                    
                    if (initialButton) {
                         // Usar initialButton.click() para ejecutar selectUserType y activar la pestaña
                         initialButton.click(); 
                    } else {
                         // Si no hay datos, activa por defecto la pestaña 'viajero'
                         selectUserType('viajero', document.querySelector('.user-type-btn'));
                    }
                });
            </script>
        </body>
        </html>
        <?php
    }
}