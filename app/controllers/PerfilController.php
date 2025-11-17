<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Auth;

class PerfilController extends Controller {

    public function index(): void {
        if (!Auth::isLoggedIn()) $this->redirect('?action=auth');
        
        $user = Auth::getCurrentUser();
        $perfilModel = $this->model('Perfil'); 
        
        $data = $perfilModel->getProfileData($user['user_type'], $user['user_id']);
        
        $this->view('PerfilView', [
            'user' => $user,
            'data' => $data,
            'errors' => $_SESSION['errors'] ?? [],
            'message' => $_SESSION['message'] ?? null
        ]);
        unset($_SESSION['errors'], $_SESSION['message']);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::isLoggedIn()) $this->redirect('?action=profile');
        
        $user = Auth::getCurrentUser();
        $data = $_POST;
        
        $errors = [];

        // Validar email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido";
        }

        // Validar contraseña (si se quiere cambiar)
        if (!empty($data['password']) || !empty($data['password_confirm'])) {
            if ($data['password'] !== $data['password_confirm']) {
                $errors[] = "Las contraseñas no coinciden";
            }
        }

        if (empty($errors)) {
            try {
                $pdo = \app\config\getPDO();

                $sql = "UPDATE transfer_viajeros SET 
                            email = :email,
                            direccion = :direccion,
                            codigoPostal = :codigoPostal,
                            ciudad = :ciudad,
                            pais = :pais";

                $params = [
                    ':email' => $data['email'],
                    ':direccion' => $data['direccion'] ?? '',
                    ':codigoPostal' => $data['codigoPostal'] ?? '',
                    ':ciudad' => $data['ciudad'] ?? '',
                    ':pais' => $data['pais'] ?? '',
                    ':id_viajero' => $user['user_id']
                ];

                if (!empty($data['password'])) {
                    $sql .= ", password = :password";
                    $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                }

                $sql .= " WHERE id_viajero = :id_viajero";

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                $_SESSION['message'] = "Perfil actualizado correctamente";
                $_SESSION['user_email'] = $data['email'];

            } catch (\PDOException $e) {
                $errors[] = "Error al actualizar el perfil: " . $e->getMessage();
                $_SESSION['errors'] = $errors;
            }
        } else {
            $_SESSION['errors'] = $errors;
        }

        $this->redirect('?action=profile');
    }
}
