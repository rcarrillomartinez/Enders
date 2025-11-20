<?php
// index.php - Punto de Entrada Único (Front Controller)
// Todas las solicitudes a la aplicación son dirigidas a este archivo, que se encarga de
// inicializar la configuración, determinar la acción solicitada y cargar el controlador adecuado.

// Inicia la sesión del usuario. Es crucial para mantener el estado de autenticación a través de las páginas.
session_start();

// Incluye los archivos de configuración y clases principales.
// Database.php: Gestiona la conexión a la base de datos (Singleton).
require_once __DIR__ . '/app/core/Database.php';
// Controller.php: Clase base de la que heredarán todos los controladores.
require_once __DIR__ . '/app/core/Controller.php';
// Auth.php: Modelo que maneja la lógica de autenticación y gestión de usuarios.
require_once __DIR__ . '/app/models/Auth.php';

// Bloque try-catch para manejar cualquier excepción global que pueda ocurrir durante la ejecución.
try {
    // Obtiene la instancia única de la conexión a la base de datos.
    $pdo = Database::getInstance()->getConnection();
    
    // Determina la acción a realizar a partir del parámetro 'action' en la URL.
    // Si no se especifica ninguna acción, se establece 'index' como valor por defecto.
    $action = $_GET['action'] ?? 'index';
    
    // --- ENRUTADOR PRINCIPAL ---

    // Comprueba si la acción solicitada pertenece al grupo de autenticación.
    if (in_array($action, ['auth', 'login', 'register', 'signup', 'logout', 'dashboard', 'profile', 'profile_update'])) {
        // Carga el controlador de autenticación.
        require_once __DIR__ . '/app/controllers/AuthController.php';
        // Crea una instancia del controlador, pasándole la conexión a la base de datos.
        $controller = new AuthController($pdo);
        
        // Selecciona el método del controlador a ejecutar según la acción.
        switch ($action) {
            case 'auth':
                $controller->index();
                break;
            case 'login':
                $controller->login();
                break;
            case 'signup':
                $controller->signup();
                break;
            case 'register':
                $controller->register();
                break;
            case 'logout':
                $controller->logout();
                break;
            case 'dashboard':
                $controller->dashboard();
                break;
            case 'profile':
                $controller->profile();
                break;
            case 'profile_update':
                $controller->updateProfile();
                break;
        }
    } else {
        // Si la acción no es de autenticación, se asume que está relacionada con las reservas.
        // Carga el controlador de reservas de transfer.
        require_once __DIR__ . '/app/controllers/TransferReservaController.php';
        // Crea una instancia del controlador.
        $controller = new TransferReservaController($pdo);
        
        // Obtiene el ID de la URL si está presente, para acciones como 'show', 'edit' o 'delete'.
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        // Enruta a la acción correspondiente en el controlador de reservas.
        if ($action === 'gestion_reservas') {
            $controller->gestion();
        } elseif ($action === 'show' && $id) {
            $controller->show($id);
        } elseif ($action === 'create') {
            $controller->create();
        // 'store' solo se ejecuta si la solicitud es de tipo POST.
        } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } elseif ($action === 'edit' && $id) {
            $controller->edit($id);
        // 'update' solo se ejecuta si la solicitud es de tipo POST.
        } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } elseif ($action === 'delete' && $id) {
            $controller->delete($id);
        } else {
            // Si ninguna de las acciones anteriores coincide, se llama al método por defecto 'index'.
            $controller->index();
        }
    }
    
} catch (Exception $e) {
    // Si ocurre cualquier excepción no capturada en el código, se muestra un mensaje de error amigable.
    // Esto previene que se muestren errores de PHP sensibles o que se rompa la página.
    echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border-radius: 4px;">';
    echo '<h2>Error</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
?>
