<?php

require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller {
    private $auth;

    public function __construct($pdo) {
        parent::__construct($pdo);
        require_once __DIR__ . '/../models/Auth.php';
        $this->auth = new Auth($pdo);
    }

    /**
     * La página de login/signup
     */
    public function index() {
        return $this->view('AuthView', [
            'page' => 'login'
        ]);
    }

    public function signup() {
        return $this->view('AuthView', [
            'page' => 'signup'
        ]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->signup();
        }

        $user_type = $_POST['user_type'] ?? 'viajero';
        $result = null;

        switch ($user_type) {
            case 'admin':
                $result = $this->auth->loginAdmin($_POST['email'] ?? '', $_POST['password'] ?? '');
                break;
            case 'hotel':
                $result = $this->auth->loginHotel($_POST['usuario'] ?? '', $_POST['password'] ?? '');
                break;
            case 'vehiculo':
                $result = $this->auth->loginVehiculo($_POST['email'] ?? '', $_POST['password'] ?? '');
                break;
            case 'viajero':
                $result = $this->auth->loginViajero($_POST['email'] ?? '', $_POST['password'] ?? '');
                break;
        }

        // Si el login es exitoso, redirige al calendario
        if ($result && $result['success']) {
            header('Location: ?action=index');
            exit();
        }

        return $this->view('AuthView', [
            'page' => 'login',
            'result' => $result,
            'user_type' => $user_type
        ]);
    }


    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->signup();
        }

        $user_type = $_POST['user_type'] ?? 'viajero';
        $result = null;

        switch ($user_type) {
            case 'hotel':
                $result = $this->auth->registerHotel(
                    $_POST['usuario'] ?? '',
                    $_POST['password'] ?? '',
                    $_POST['id_zona'] ?? null
                );
                break;

            case 'vehiculo':
                $result = $this->auth->registerVehiculo(
                    $_POST['email_conductor'] ?? '',
                    $_POST['descripcion'] ?? '',
                    $_POST['password'] ?? ''
                );
                break;

            case 'viajero':
                $result = $this->auth->registerViajero(
                    $_POST['email'] ?? '',
                    $_POST['nombre'] ?? '',
                    $_POST['apellido1'] ?? '',
                    $_POST['apellido2'] ?? '',
                    $_POST['direccion'] ?? '',
                    $_POST['codigoPostal'] ?? '',
                    $_POST['ciudad'] ?? '',
                    $_POST['pais'] ?? '',
                    $_POST['password'] ?? ''
                );
                break;
        }

        // Si el registro es exitoso, redirige al calendario
        if ($result && $result['success']) {
            // Auto-login el usuario recién registrado
            if ($user_type === 'viajero') {
                $loginResult = $this->auth->loginViajero(
                    $_POST['email'] ?? '',
                    $_POST['password'] ?? ''
                );
                if ($loginResult['success']) {
                    header('Location: ?action=index');
                    exit();
                }
            } elseif ($user_type === 'vehiculo') {
                $loginResult = $this->auth->loginVehiculo(
                    $_POST['email_conductor'] ?? '',
                    $_POST['password'] ?? ''
                );
                if ($loginResult['success']) {
                    header('Location: ?action=index');
                    exit();
                }
            } elseif ($user_type === 'hotel') {
                $loginResult = $this->auth->loginHotel(
                    $_POST['usuario'] ?? '',
                    $_POST['password'] ?? ''
                );
                if ($loginResult['success']) {
                    header('Location: ?action=index');
                    exit();
                }
            }
        }

        return $this->view('AuthView', [
            'page' => 'signup',
            'result' => $result,
            'user_type' => $user_type
        ]);
    }

    /**
     * Logout
     */
    public function logout() {
        $this->auth->logout();
        header('Location: ?action=auth');
        exit();
    }

    public function dashboard() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        $user = Auth::getCurrentUser();
        return $this->view('AuthDashboardView', [
            'user' => $user
        ]);
    }
}
?>
