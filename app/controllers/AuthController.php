<?php

// Incluye los archivos necesarios del core y los modelos.
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Perfil.php';

// Clase AuthController: Maneja las operaciones de autenticación de usuarios.
class AuthController extends Controller {

    /**
     * Constructor de AuthController.
     * @param PDO $pdo Instancia de la conexión a la base de datos.
     */
    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    /**
     * Muestra la página de inicio de sesión por defecto.
     */
    public function index() {
        $this->view('AuthView', ['page' => 'login']);
    }

    /**
     * Muestra la página de registro.
     */
    public function signup() {
        $this->view('AuthView', ['page' => 'signup']);
    }

    /**
     * Procesa el intento de inicio de sesión.
     * Redirige a la página de autenticación si no es una solicitud POST.
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=auth');
            exit;
        }

        $userType = $_POST['user_type'] ?? '';
        $auth = new Auth($this->pdo);
        // Inicializa un array para almacenar el resultado del login.
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

        // Muestra la vista de autenticación con el resultado del login.
        $this->view('AuthView', ['page' => 'login', 'result' => $result]);
    }

    /**
     * Procesa el intento de registro de un nuevo usuario.
     * Redirige a la página de registro si no es una solicitud POST.
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=signup');
            exit;
        }

        $userType = $_POST['user_type'] ?? '';
        $auth = new Auth($this->pdo);
        // Inicializa un array para almacenar el resultado del registro.
        $result = [];

        // Este es un ejemplo simplificado. Se debería añadir una validación adecuada.
        switch ($userType) {
            case 'viajero':
                $result = $auth->registerViajero($_POST['email'], $_POST['nombre'], $_POST['apellido1'], $_POST['apellido2'], $_POST['direccion'], $_POST['codigoPostal'], $_POST['ciudad'], $_POST['pais'], $_POST['password']);
                break;
            // Añadir casos para otros tipos de usuario si es necesario.
            default:
                $result = ['success' => false, 'message' => 'Tipo de usuario no válido para registro.'];
        }

        // Muestra la vista de autenticación con el resultado del registro.
        $this->view('AuthView', ['page' => 'signup', 'result' => $result]);
    }

    /**
     * Cierra la sesión del usuario actual y redirige a la página de inicio de sesión.
     */
    public function logout() {
        $auth = new Auth($this->pdo);
        $auth->logout();
        header('Location: ?action=auth');
        exit;
    }

    /**
     * Muestra el panel de control (dashboard) del usuario.
     * Redirige a la página de inicio de sesión si el usuario no está logueado.
     */
    public function dashboard() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit;
        }
        // Pasa la información del usuario actual a la vista del dashboard.
        $this->view('AuthDashboardView', ['user' => Auth::getCurrentUser()]);
    }

    /**
     * Muestra la página de perfil del usuario.
     * Redirige a la página de inicio de sesión si el usuario no está logueado.
     */
    public function profile() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit;
        }
        $user = Auth::getCurrentUser();
        // Obtiene los datos del perfil del usuario desde el modelo.
        $perfilModel = new Perfil($this->pdo);
        $profileData = $perfilModel->getProfileData($user['user_type'], $user['user_id']);

        // Muestra la vista del perfil con los datos del usuario.
        $this->view('PerfilView', ['user' => $user, 'data' => $profileData, 'errors' => [], 'message' => '']);
    }

    /**
     * Procesa la actualización de los datos del perfil del usuario.
     * Redirige al dashboard si el usuario no está logueado o no es una solicitud POST.
     */
    public function updateProfile() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=dashboard');
            exit;
        }
        $user = Auth::getCurrentUser();
        $perfilModel = new Perfil($this->pdo);
        // Intenta actualizar el perfil con los datos recibidos por POST.
        $result = $perfilModel->updateProfile($user['user_type'], $user['user_id'], $_POST);

        // Vuelve a obtener los datos del perfil para mostrar los valores actualizados.
        $profileData = $perfilModel->getProfileData($user['user_type'], $user['user_id']);

        // Muestra la vista del perfil con los datos actualizados y los mensajes de resultado.
        $this->view('PerfilView', [
            'user' => Auth::getCurrentUser(), // Vuelve a obtener el usuario por si el nombre de sesión ha cambiado.
            'data' => $profileData,
            'errors' => !$result['success'] ? [$result['message']] : [],
            'message' => $result['success'] ? $result['message'] : ''
        ]);
    }
}