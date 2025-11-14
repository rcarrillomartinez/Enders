<?php

require_once __DIR__ . '/app/core/Database.php';


try {
    $pdo = Database::getInstance()->getConnection();
    
    require_once __DIR__ . '/app/controllers/TransferReservaController.php';
    
    $controller = new TransferReservaController($pdo);
    
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    if ($action === 'show' && $id) {
        $controller->show($id);
    } elseif ($action === 'create') {
        $controller->create();
    } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } else {
        $controller->index();
    }
    
} catch (Exception $e) {
    echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border-radius: 4px;">';
    echo '<h2>Error</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
