<?php
// Arranque de la aplicación

// --- Mostrar errores en desarrollo ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Carga automática de clases usando namespaces ---
spl_autoload_register(function($class) {
    $prefix = 'app\\';
    $base_dir = __DIR__ . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// --- Cargar configuración ---
require_once __DIR__ . '/app/config/Config.php';

// --- Iniciar sesión ---
session_start();

// --- Obtener la acción desde la URL ---
$action = $_GET['action'] ?? 'dashboard';
$pdo = \app\config\getPDO(); // Obtenemos PDO desde config

// --- Mapear acciones a controladores ---
$routes = [
    'home' => ['\app\controllers\HomeController', 'index'],
    'auth' => ['\app\controllers\AuthController', 'index'],
    'login' => ['\app\controllers\AuthController', 'login'],
    'signup' => ['\app\controllers\AuthController', 'signup'],
    'register' => ['\app\controllers\AuthController', 'register'],
    'logout' => ['\app\controllers\AuthController', 'logout'],
    'dashboard' => ['\app\controllers\UserController', 'dashboard'],
    'perfil' => ['\app\controllers\UserController', 'perfil'],
    'updatePerfil' => ['\app\controllers\UserController', 'updatePerfil'],
    'calendar' => ['\app\controllers\TransferReservaController', 'index'],
    'showReserva' => ['\app\controllers\TransferReservaController', 'show'],
    'createReserva' => ['\app\controllers\TransferReservaController', 'create'],
    'storeReserva' => ['\app\controllers\TransferReservaController', 'store']
];

// --- Ejecutar la acción ---
if (isset($routes[$action])) {
    [$controllerClass, $method] = $routes[$action];
    $controller = new $controllerClass($pdo);

    // Pasar parámetros según la acción
    switch ($action) {
        case 'showReserva':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                die('ID de reserva no proporcionado');
            }
            $controller->$method($id);
            break;

        default:
            $controller->$method();
            break;
    }

} else {
    // Acción no encontrada
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - Página no encontrada</h1>";
}
