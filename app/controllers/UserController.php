<?php
namespace app\controllers;

use app\core\Controller;
use app\models\TransferReserva;
use app\models\Auth;
use PDO;

class UserController extends Controller {
    private $reservaModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->reservaModel = new TransferReserva($pdo);
    }

    private function ensureAuth() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }
    }

    public function dashboard() {
        $this->ensureAuth();

        $user = Auth::getCurrentUser();

        if ($user['user_type'] === 'admin') {
            $reservas = $this->reservaModel->getAll();
        } else {
            $reservas = $this->reservaModel->getByUser($user['user_email']);
        }

        $this->view('UserDashboardView', [
            'user' => $user,
            'reservas' => $reservas
        ]);
    }

    public function perfil() {
        $this->ensureAuth();
        $userSession = Auth::getCurrentUser(); // Esto solo tiene id, type y email

        // Traemos los datos completos del viajero desde la base de datos
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_viajeros WHERE id_viajero = :id");
        $stmt->execute([':id' => $userSession['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ahora $user contiene nombre, apellido1, apellido2, email, etc.
        $this->view('PerfilView', ['user' => $user]);
    }

    public function updatePerfil() {
        $this->ensureAuth();
        $user = Auth::getCurrentUser();
        $data = $_POST;
        $res = Auth::updateProfile($user['email'], $data);
        $_SESSION['message'] = $res['message'] ?? '';
        header('Location: ?action=perfil');
    }
}
