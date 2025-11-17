<?php

require_once __DIR__ . '/../core/Controller.php';

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
        
        $perfilModel = $this->model('Perfil');
        $result = $perfilModel->updateProfile($user['user_type'], $user['user_id'], $data);
        
        if ($result['success']) {
            $_SESSION['message'] = $result['message'];
        } else {
            $_SESSION['errors'] = [$result['message']];
        }
        
        $this->redirect('?action=profile');
    }
}
