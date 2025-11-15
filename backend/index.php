<?php
// index.php — Enrutador principal del backend

// --- CORS (solo para desarrollo) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');

// Incluir controladores (usar rutas absolutas con __DIR__)
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/UsuarioController.php';
require_once __DIR__ . '/src/controllers/ReservaController.php';
require_once __DIR__ . '/src/controllers/HotelController.php';

// Obtener datos de la petición
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$module = $_GET['module'] ?? '';
$action = $_GET['action'] ?? '';

// Instanciar controladores
$authController = new AuthController();
$usuarioController = new UsuarioController();
$reservaController = new ReservaController();
$hotelController = new HotelController();

// Ruteo dinámico por módulo y acción
switch ($module) {
    case 'auth':
        if ($action === 'login' && $method === 'POST') {
            $authController->login($input);
        } elseif ($action === 'logout' && $method === 'POST') {
            $authController->logout();
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Acción de autenticación no válida']);
        }
        break;

    case 'usuario':
        if ($action === 'crear' && $method === 'POST') {
            $usuarioController->crear($input);
        } elseif ($action === 'modificar' && $method === 'POST') {
            $usuarioController->modificar($input);
        } elseif ($action === 'eliminar' && $method === 'POST') {
            $usuarioController->eliminar($input);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Acción de usuario no válida']);
        }
        break;

    case 'reserva':
        if ($action === 'crear' && $method === 'POST') {
            $reservaController->crear($input);
        } elseif ($action === 'modificar' && $method === 'POST') {
            $reservaController->modificar($input);
        } elseif ($action === 'cancelar' && $method === 'POST') {
            $reservaController->cancelar($input);
        } elseif ($action === 'listarPorUsuario' && $method === 'POST') {
            $reservaController->listarPorUsuario($input);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Acción de reserva no válida']);
        }
        break;

    case 'hotel':
        if ($action === 'listar' && $method === 'GET') {
            $hotelController->listar();
        } elseif ($action === 'obtener' && $method === 'POST') {
            $hotelController->obtener($input);
        } elseif ($action === 'crear' && $method === 'POST') {
            $hotelController->crear($input);
        } elseif ($action === 'modificar' && $method === 'POST') {
            $hotelController->modificar($input);
        } elseif ($action === 'eliminar' && $method === 'POST') {
            $hotelController->eliminar($input);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Acción de hotel no válida']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ruta no encontrada']);
        break;
}
