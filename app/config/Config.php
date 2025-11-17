<?php
namespace app\config;

use PDO;

// --- Configuración general ---
define('APP_NAME', 'Reservas MVC');
define('APP_URL', 'http://localhost'); // Cambiar según tu dominio
define('APP_DEBUG', true);

// --- Base de datos ---
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'uoc_transfers');
define('DB_USER', 'roqueCM');
define('DB_PASS', '8591RCM'); 
define('DB_CHARSET', 'utf8mb4');

// --- Configuración de correo (opcional para notificaciones) ---
define('MAIL_FROM', 'no-reply@reservas.com');
define('MAIL_FROM_NAME', 'Reservas MVC');

// --- Conexión PDO ---
function getPDO(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (\PDOException $e) {
            if (APP_DEBUG) {
                die("❌ Error de conexión a la base de datos: " . $e->getMessage());
            } else {
                die("❌ Error de conexión a la base de datos");
            }
        }
    }
    return $pdo;
}

// --- Rutas amigables / URL limpias ---
define('URL_HOME', APP_URL . '/index.php?action=dashboard');
define('URL_LOGIN', APP_URL . '/index.php?action=auth');
define('URL_LOGOUT', APP_URL . '/index.php?action=logout');
define('URL_REGISTER', APP_URL . '/index.php?action=signup');
define('URL_ADMIN_CALENDAR', APP_URL . '/index.php?action=calendar');

// --- Parámetros adicionales ---
define('SESSION_LIFETIME', 3600); // 1 hora
