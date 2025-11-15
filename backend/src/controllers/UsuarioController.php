<?php
require_once __DIR__ . '/../models/DBConnection.php';
require_once __DIR__ . '/../models/UsuarioSistema.php';

class UsuarioController {
    private $model;

    public function __construct() {
        $db = new DBConnection();
        $this->model = new UsuarioSistema($db);
    }

    public function crear($data) {
        $result = $this->model->crear($data);
        if (is_array($result) && isset($result['error'])) {
            echo json_encode(['success' => false, 'message' => $result['error']]);
        } else {
            echo json_encode(['success' => true]);
        }
    }

    public function modificar($data) {
        $id = $data['id_usuario'] ?? 0;
        unset($data['id_usuario']);
        echo json_encode(['success' => $this->model->modificar($id, $data)]);
    }

    public function eliminar($data) {
        echo json_encode(['success' => $this->model->eliminar($data['id_usuario'])]);
    }
}
?>
