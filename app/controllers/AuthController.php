<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Auth;

class AuthController extends Controller {
    private $auth;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->auth = new Auth($pdo);
    }

    // --- Página de login ---
    public function index() {
        if (Auth::isLoggedIn()) {
            $user = Auth::getCurrentUser();
            if ($user['user_type'] === 'admin') {
                header('Location: ?action=calendar');
            } else {
                header('Location: ?action=dashboard');
            }
            exit();
        }
        $this->view('AuthView', ['page' => 'login']);
    }

    // --- Login ---
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->index();

        $user_type = $_POST['user_type'] ?? 'viajero';
        $result = null;

        if ($user_type === 'admin') {
            $result = $this->auth->loginAdmin($_POST['email'] ?? '', $_POST['password'] ?? '');
        } else {
            $result = $this->auth->loginViajero($_POST['email'] ?? '', $_POST['password'] ?? '');
        }

        if ($result['success']) {
            $user = Auth::getCurrentUser();
            if ($user['user_type'] === 'admin') {
                header('Location: ?action=calendar');
            } else {
                header('Location: ?action=dashboard');
            }
            exit();
        }

        $this->view('AuthView', [
            'page' => 'login',
            'result' => $result,
            'user_type' => $user_type
        ]);
    }

    // --- Registro de viajeros ---
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->view('AuthView', ['page' => 'signup']);

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellido1' => $_POST['apellido1'] ?? '',
            'apellido2' => $_POST['apellido2'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        $result = $this->auth->registerViajero($data);

        if ($result['success']) {
            // Auto-login después del registro
            $this->auth->loginViajero($data['email'], $data['password']);
            header('Location: ?action=dashboard');
            exit();
        }

        $this->view('AuthView', [
            'page' => 'signup',
            'result' => $result
        ]);
    }

    // --- Logout ---
    public function logout() {
        Auth::logout();
        header('Location: ?action=auth');
        exit();
    }
}
