<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Perfil.php';

class AuthController extends Controller {

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    public function index() {
        $this->view('AuthView', ['page' => 'login']);
    }

    public function signup() {
        $this->view('AuthView', ['page' => 'signup']);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=auth');
            exit;
        }

        $userType = $_POST['user_type'] ?? '';
        $auth = new Auth($this->pdo);
        $result = [];

        switch ($userType) {
            case 'viajero':
                $result = $auth->loginViajero($_POST['email'], $_POST['password']);
                break;
            case 'vehiculo':
                $result = $auth->loginVehiculo($_POST['email'], $_POST['password']);
                break;
            case 'hotel':
                $result = $auth->loginHotel($_POST['usuario'], $_POST['password']);
                break;
            case 'admin':
                $result = $auth->loginAdmin($_POST['email'], $_POST['password']);
                break;
            default:
                $result = ['success' => false, 'message' => 'Tipo de usuario no válido.'];
        }

        $this->view('AuthView', ['page' => 'login', 'result' => $result]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=signup');
            exit;
        }

        $userType = $_POST['user_type'] ?? '';
        $auth = new Auth($this->pdo);
        $result = [];

        // This is a simplified example. You should add proper validation.
        switch ($userType) {
            case 'viajero':
                $result = $auth->registerViajero($_POST['email'], $_POST['nombre'], $_POST['apellido1'], $_POST['apellido2'], $_POST['direccion'], $_POST['codigoPostal'], $_POST['ciudad'], $_POST['pais'], $_POST['password']);
                break;
            // Add cases for other user types if needed
            default:
                $result = ['success' => false, 'message' => 'Tipo de usuario no válido para registro.'];
        }

        $this->view('AuthView', ['page' => 'signup', 'result' => $result]);
    }

    public function logout() {
        $auth = new Auth($this->pdo);
        $auth->logout();
        header('Location: ?action=auth');
        exit;
    }

    public function dashboard() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit;
        }
        $this->view('AuthDashboardView', ['user' => Auth::getCurrentUser()]);
    }

    public function profile() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit;
        }
        $user = Auth::getCurrentUser();
        $perfilModel = new Perfil($this->pdo);
        $profileData = $perfilModel->getProfileData($user['user_type'], $user['user_id']);

        $this->view('PerfilView', ['user' => $user, 'data' => $profileData, 'errors' => [], 'message' => '']);
    }

    public function updateProfile() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=dashboard');
            exit;
        }
        $user = Auth::getCurrentUser();
        $perfilModel = new Perfil($this->pdo);
        $result = $perfilModel->updateProfile($user['user_type'], $user['user_id'], $_POST);

        // Re-fetch data to show updated values
        $profileData = $perfilModel->getProfileData($user['user_type'], $user['user_id']);

        $this->view('PerfilView', [
            'user' => Auth::getCurrentUser(), // Re-get user in case session name changed
            'data' => $profileData,
            'errors' => !$result['success'] ? [$result['message']] : [],
            'message' => $result['success'] ? $result['message'] : ''
        ]);
    }
}