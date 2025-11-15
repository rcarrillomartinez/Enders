<?php
require_once __DIR__ . '/../models/DBConnection.php';
require_once __DIR__ . '/../models/UsuarioSistema.php';

session_start();

class AuthController {
    private $model;

    public function __construct() {
        $db = new DBConnection();
        $this->model = new UsuarioSistema($db);
    }

    public function login($data) {
        $user = $this->model->login($data['email'], $data['password']);
        if ($user) {
            $_SESSION['user'] = $user;
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Credenciales inválidas']);
        }
    }

    public function logout() {
        session_destroy();
        echo json_encode(['success' => true]);
    }
}
?>
