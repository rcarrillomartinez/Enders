<?php
// PerfilController.php - Controlador para gestionar el perfil del usuario.

// Incluye la clase base del controlador y los modelos necesarios.
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Perfil.php';

/**
 * Clase PerfilController
 * Maneja las solicitudes relacionadas con la visualización y actualización del perfil de usuario.
 */
class PerfilController extends Controller {

    /**
     * Muestra la página del perfil del usuario.
     * Carga los datos del perfil y los pasa a la vista.
     */
    public function index(): void {
        // Si el usuario no ha iniciado sesión, redirige a la página de autenticación.
        if (!Auth::isLoggedIn()) $this->redirect('?action=auth');
        
        // Obtiene la información del usuario actual de la sesión.
        $user = Auth::getCurrentUser();
        // Crea una instancia del modelo de perfil.
        $perfilModel = new Perfil($this->pdo); 
        
        // Obtiene los datos completos del perfil desde la base de datos.
        $data = $perfilModel->getProfileData($user['user_type'], $user['user_id']);
        
        // Carga la vista 'PerfilView' y le pasa los datos del usuario, su perfil,
        // y cualquier mensaje de error o éxito almacenado en la sesión.
        $this->view('PerfilView', [
            'user' => $user,
            'data' => $data,
            'errors' => $_SESSION['errors'] ?? [],
            'message' => $_SESSION['message'] ?? null
        ]);
        // Limpia los mensajes de la sesión después de mostrarlos para que no aparezcan de nuevo.
        unset($_SESSION['errors'], $_SESSION['message']);
    }

    /**
     * Procesa la solicitud de actualización del perfil.
     * Valida la solicitud y actualiza los datos en la base de datos.
     */
    public function update(): void {
        // Solo procesa la solicitud si es de tipo POST y el usuario ha iniciado sesión.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::isLoggedIn()) $this->redirect('?action=profile');
        
        // Obtiene los datos del usuario y los datos enviados por el formulario.
        $user = Auth::getCurrentUser();
        $data = $_POST;
        
        // Crea una instancia del modelo y llama al método para actualizar el perfil.
        $perfilModel = new Perfil($this->pdo);
        $result = $perfilModel->updateProfile($user['user_type'], $user['user_id'], $data);
        
        // Almacena el mensaje de resultado (éxito o error) en la sesión.
        if ($result['success']) {
            $_SESSION['message'] = $result['message'];
        } else {
            $_SESSION['errors'] = [$result['message']];
        }
        
        // Redirige de vuelta a la página del perfil para mostrar el resultado.
        $this->redirect('?action=profile');
    }
}
