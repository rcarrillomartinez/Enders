<?php
require_once __DIR__ . '/../models/DBConnection.php';
require_once __DIR__ . '/../models/Hotel.php';

class HotelController {
    private $model;

    public function __construct() {
        $db = new DBConnection();
        $this->model = new Hotel($db);
    }

    public function listar() {
        echo json_encode($this->model->listar());
    }

    public function obtener($data) {
        $id = $data['id'] ?? 0;
        echo json_encode($this->model->obtener($id));
    }

    public function crear($data) {
        echo json_encode(['success' => $this->model->crear($data)]);
    }

    public function modificar($data) {
        $id = $data['id_hotel'] ?? 0;
        echo json_encode(['success' => $this->model->modificar($id, $data)]);
    }

    public function eliminar($data) {
        $id = $data['id_hotel'] ?? 0;
        echo json_encode(['success' => $this->model->eliminar($id)]);
    }
}
?>
