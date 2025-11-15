<?php
// Punto central para llamadas API
// Redirige a controlador correspondiente según parámetro 'module' y 'action'.
$module = $_GET['module'] ?? '';
$action = $_GET['action'] ?? '';

switch($module){
    case 'hotel':
        require_once __DIR__."/../controllers/HotelController.php";
        break;
    case 'reserva':
        require_once __DIR__."/../controllers/ReservaController.php";
        break;
    case 'usuario':
        require_once __DIR__."/../controllers/UsuarioController.php";
        break;
    case 'auth':
        require_once __DIR__."/../controllers/AuthController.php";
        break;
    default:
        echo json_encode(['error'=>'Módulo no encontrado']);
}
?>
