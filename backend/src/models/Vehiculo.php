<?php
require_once 'DBConnection.php';
class Vehiculo {
    private $db;
    public function __construct(DBConnection $db) { $this->db = $db; }

    public function listar() {
        $res = $this->db->query("SELECT * FROM transfer_vehiculo");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtener($id) {
        $id = $this->db->escape($id);
        $res = $this->db->query("SELECT * FROM transfer_vehiculo WHERE id_vehiculo=$id");
        return $res ? $res->fetch_assoc() : null;
    }

    public function crear($data) {
        $desc = $this->db->escape($data['Descripción']);
        $sql = "INSERT INTO transfer_vehiculo (Descripción) VALUES ('$desc')";
        return $this->db->query($sql);
    }

    public function modificar($id, $data) {
        $id = $this->db->escape($id);
        $desc = $this->db->escape($data['Descripción']);
        $sql = "UPDATE transfer_vehiculo SET Descripción='$desc' WHERE id_vehiculo=$id";
        return $this->db->query($sql);
    }

    public function eliminar($id) {
        $id = $this->db->escape($id);
        return $this->db->query("DELETE FROM transfer_vehiculo WHERE id_vehiculo=$id");
    }
}
?>
