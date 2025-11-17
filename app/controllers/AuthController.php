<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../views/AuthView.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../core/Database.php'; // Singleton Database

class AuthController extends Controller {

    protected  PDO $db;

    public function __construct() {
        // Obtener la conexión PDO usando el singleton
        $database = Database::getInstance(); 
        $this->db = $database->getConnection();

        // Iniciar sesión si no está iniciada
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ------------------------------
    // Métodos de formularios
    // ------------------------------

    public function auth(): void {
        $result = $_SESSION['auth_result'] ?? null;
        $formData = $_SESSION['auth_data'] ?? []; 
        
        AuthView::renderLoginForm($result, $formData); 
        unset($_SESSION['auth_result'], $_SESSION['auth_data']);
    }

    public function signup(): void {
        $result = $_SESSION['auth_result'] ?? null;
        $formData = $_SESSION['auth_data'] ?? []; 

        AuthView::renderSignupForm($result, $formData); 
        unset($_SESSION['auth_result'], $_SESSION['auth_data']);
    }

    // ------------------------------
    // Login
    // ------------------------------

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?action=auth');
        }

        $userType = $_POST['user_type'] ?? '';
        $identifier = $_POST['identifier'] ?? '';
        $password = $_POST['password'] ?? '';

        $authModel = new Auth($this->db);
        $result = $authModel->login($userType, $identifier, $password);

        if ($result['success']) {
            $this->redirect('?action=index');
        } else {
            $_SESSION['auth_result'] = $result;
            $_SESSION['auth_data'] = [
                'user_type' => $userType,
                'identifier' => $identifier
            ];
            $this->redirect('?action=auth');
        }
    }

    // ------------------------------
    // Registro
    // ------------------------------

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?action=signup');
        }

        $userType = strtolower($_POST['user_type'] ?? '');
        $authModel = new Auth($this->db);
        $result = ['success' => false, 'message' => 'Tipo de registro no soportado o inválido.'];

        match ($userType) {
            'viajero' => $result = $authModel->registerViajero($_POST),
            'vehiculo' => $result = $authModel->registerVehiculo($_POST),
            'hotel' => $result = $authModel->registerHotel($_POST),
            'admin' => $result = $authModel->registerAdmin($_POST),
            default => $result = ['success' => false, 'message' => 'Tipo de usuario inválido.']
        };

        $_SESSION['auth_result'] = $result;
        $_SESSION['auth_data'] = $_POST;
        unset($_SESSION['auth_data']['password']); 

        $this->redirect('?action=signup');
    }

    // ------------------------------
    // Logout
    // ------------------------------

    public function logout(): void {
        Auth::logout(); // Método estático
        $this->redirect('?action=auth');
    }

    // ------------------------------
    // Dashboard o página principal
    // ------------------------------

    public function dashboard(): void {
        $this->redirect('?action=index');
    }

    // ------------------------------
    // Método de redirección
    // ------------------------------

    protected function redirect(string $url): void {
        header("Location: $url");
        exit;
    }
}

?>
