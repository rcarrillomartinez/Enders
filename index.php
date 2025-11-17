<?php
session_start();

require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/models/Auth.php';

try {
    $pdo = Database::getInstance()->getConnection();
    
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    
    if (in_array($action, ['auth', 'login', 'register', 'signup', 'logout', 'dashboard', 'profile', 'profile_update'])) {
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
            case 'profile':
                $controller->profile();
                break;
            case 'profile_update':
                $controller->updateProfile();
                break;
        }
    } else {
        require_once __DIR__ . '/app/controllers/TransferReservaController.php';
        $controller = new TransferReservaController($pdo);
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if ($action === 'gestion_reservas') {
            $controller->gestion();
        } elseif ($action === 'show' && $id) {
            $controller->show($id);
        } elseif ($action === 'create') {
            $controller->create();
        } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } elseif ($action === 'edit' && $id) {
            $controller->edit($id);
        } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } elseif ($action === 'delete' && $id) {
            $controller->delete($id);
        } else {
            $controller->index();
        }
    }
} catch (Exception $e) {
    echo '<div style="background-color:#f8d7da;color:#721c24;padding:15px;margin:20px;border-radius:4px;">';
    echo '<h2>Error Fatal de Aplicación</h2><p>'.htmlspecialchars($e->getMessage()).'</p>';
    echo '</div>';
}
