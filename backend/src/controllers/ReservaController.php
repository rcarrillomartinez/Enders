<?php
require_once __DIR__ . '/../models/DBConnection.php';
require_once __DIR__ . '/../models/Reserva.php';

class ReservaController {
    private $model;

    public function __construct() {
        $db = new DBConnection();
        $this->model = new Reserva($db);
    }

    public function crear($data) {
        echo json_encode(['success' => $this->model->crear($data)]);
    }

    public function modificar($data) {
        $id = $data['id_reserva'] ?? 0;
        unset($data['id_reserva']);
        echo json_encode(['success' => $this->model->modificar($id, $data)]);
    }

    public function cancelar($data) {
        echo json_encode(['success' => $this->model->cancelar($data['id_reserva'])]);
    }

    public function listarPorUsuario($data) {
        $id_usuario = $data['id_usuario'] ?? 0;
        echo json_encode(iterator_to_array($this->model->listarPorUsuario($id_usuario)));
    }

    public function ver($data) {
        $id = $data['id_reserva'] ?? 0;
        echo json_encode($this->model->ver($id));
    }
}
?>
