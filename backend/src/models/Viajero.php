<?php
require_once 'DBConnection.php';
class Viajero {
    private $db;
    public function __construct(DBConnection $db) { $this->db = $db; }

    public function listar() {
        $res = $this->db->query("SELECT * FROM transfer_viajeros");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtener($id) {
        $id = $this->db->escape($id);
        $res = $this->db->query("SELECT * FROM transfer_viajeros WHERE id_viajero=$id");
        return $res ? $res->fetch_assoc() : null;
    }

    public function crear($data) {
        $nombre = $this->db->escape($data['nombre']);
        $apellido1 = $this->db->escape($data['apellido1']);
        $apellido2 = $this->db->escape($data['apellido2']);
        $direccion = $this->db->escape($data['direccion']);
        $cp = $this->db->escape($data['codigoPostal']);
        $ciudad = $this->db->escape($data['ciudad']);
        $pais = $this->db->escape($data['pais']);
        $email = $this->db->escape($data['email']);
        $sql = "INSERT INTO transfer_viajeros (nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, email) 
                VALUES ('$nombre','$apellido1','$apellido2','$direccion','$cp','$ciudad','$pais','$email')";
        return $this->db->query($sql);
    }

    public function modificar($id, $data) {
        $id = $this->db->escape($id);
        $nombre = $this->db->escape($data['nombre']);
        $apellido1 = $this->db->escape($data['apellido1']);
        $apellido2 = $this->db->escape($data['apellido2']);
        $direccion = $this->db->escape($data['direccion']);
        $cp = $this->db->escape($data['codigoPostal']);
        $ciudad = $this->db->escape($data['ciudad']);
        $pais = $this->db->escape($data['pais']);
        $email = $this->db->escape($data['email']);
        $sql = "UPDATE transfer_viajeros 
                SET nombre='$nombre', apellido1='$apellido1', apellido2='$apellido2', direccion='$direccion', 
                codigoPostal='$cp', ciudad='$ciudad', pais='$pais', email='$email' 
                WHERE id_viajero=$id";
        return $this->db->query($sql);
    }

    public function eliminar($id) {
        $id = $this->db->escape($id);
        return $this->db->query("DELETE FROM transfer_viajeros WHERE id_viajero=$id");
    }
}
?>
