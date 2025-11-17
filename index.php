<?php
session_start();

require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/models/Auth.php';

try {
    $pdo = Database::getInstance()->getConnection();
    $action = $_GET['action'] ?? 'index';
    $reserva_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0; 

    if (!Auth::isLoggedIn() && !in_array($action, ['auth', 'login', 'register', 'signup'])) {
        $action = 'auth';
    }

    if (in_array($action, ['auth','login','signup','register','logout','dashboard'])) {
        require_once __DIR__ . '/app/controllers/AuthController.php';
        $controller = new AuthController($pdo);
        switch ($action) {
            case 'auth': case 'login': case 'signup': case 'register': case 'logout':
                $controller->{$action}();
                break;
            case 'dashboard':
                $controller->dashboard(); 
                break;
        }
    } elseif (in_array($action, ['profile','profile_update'])) {
        require_once __DIR__ . '/app/controllers/PerfilController.php';
        $controller = new PerfilController($pdo);
        switch ($action) {
            case 'profile': $controller->index(); break;
            case 'profile_update':
                if($_SERVER['REQUEST_METHOD']==='POST') $controller->update();
                break;
        }
    } else {
        require_once __DIR__ . '/app/controllers/TransferReservaController.php';
        $controller = new TransferReservaController($pdo);
        switch ($action) {
            case 'index': $controller->index(); break;
            case 'create': $controller->create(); break;
            case 'store': if($_SERVER['REQUEST_METHOD']==='POST') $controller->store(); break;
            case 'edit': if($reserva_id>0) $controller->edit($reserva_id); break;
            case 'editReserva': $controller->editReserva(); break;
            case 'cancel': if($reserva_id>0) $controller->cancel($reserva_id); break;
            default: $controller->index();
        }
    }
} catch (Exception $e) {
    echo '<div style="background-color:#f8d7da;color:#721c24;padding:15px;margin:20px;border-radius:4px;">';
    echo '<h2>Error Fatal de Aplicación</h2><p>'.htmlspecialchars($e->getMessage()).'</p>';
    echo '</div>';
}
