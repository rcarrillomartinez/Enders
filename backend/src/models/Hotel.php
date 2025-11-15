<?php
require_once 'DBConnection.php';
class Hotel {
    private $db;
    public function __construct(DBConnection $db) { $this->db = $db; }

    public function listar() {
        $res = $this->db->query("SELECT * FROM tranfer_hotel");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtener($id) {
        $id = $this->db->escape($id);
        $res = $this->db->query("SELECT * FROM tranfer_hotel WHERE id_hotel=$id");
        return $res ? $res->fetch_assoc() : null;
    }

    public function crear($data) {
        $id_zona = $this->db->escape($data['id_zona']);
        $comision = $this->db->escape($data['Comision']);
        $sql = "INSERT INTO tranfer_hotel (id_zona, Comision) VALUES ('$id_zona', '$comision')";
        return $this->db->query($sql);
    }

    public function modificar($id, $data) {
        $id = $this->db->escape($id);
        $id_zona = $this->db->escape($data['id_zona']);
        $comision = $this->db->escape($data['Comision']);
        $sql = "UPDATE tranfer_hotel SET id_zona='$id_zona', Comision='$comision' WHERE id_hotel=$id";
        return $this->db->query($sql);
    }

    public function eliminar($id) {
        $id = $this->db->escape($id);
        return $this->db->query("DELETE FROM tranfer_hotel WHERE id_hotel=$id");
    }
}
?>
