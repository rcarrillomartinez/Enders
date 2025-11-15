<?php
require_once 'DBConnection.php';
class UsuarioSistema {
    private $db;
    public function __construct(DBConnection $db) { $this->db = $db; }

    public function login($email, $password) {
        $email = $this->db->escape($email);
        $res = $this->db->query("SELECT * FROM transfer_usuarios_sistema WHERE email = '$email'");
        if ($row = $res->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    public function crear($data) {
        $email = $this->db->escape($data['email']);
        $nombre = $this->db->escape($data['nombre']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $rol = $this->db->escape($data['rol']);
        $id_referencia = isset($data['id_referencia']) ? $this->db->escape($data['id_referencia']) : 'NULL';

        $sql = "INSERT INTO transfer_usuarios_sistema (email, password, nombre, rol, id_referencia) 
                VALUES ('$email', '$password', '$nombre', '$rol', $id_referencia)";

        try {
            return $this->db->query($sql);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                // Error por email duplicado
                return ['success' => false, 'error' => 'El email ya está registrado.'];
            } else {
                throw $e; // Re-lanzar otra excepción inesperada
            }
        }
    }

    public function modificar($id, $data) {
        $set = [];
        if (isset($data['email'])) $set[] = "email='" . $this->db->escape($data['email']) . "'";
        if (isset($data['nombre'])) $set[] = "nombre='" . $this->db->escape($data['nombre']) . "'";
        if (isset($data['password'])) $set[] = "password='" . password_hash($data['password'], PASSWORD_DEFAULT) . "'";
        if (isset($data['rol'])) $set[] = "rol='" . $this->db->escape($data['rol']) . "'";
        if (isset($data['id_referencia'])) $set[] = "id_referencia='" . $this->db->escape($data['id_referencia']) . "'";
        $sql = "UPDATE transfer_usuarios_sistema SET " . implode(", ", $set) . " WHERE id_usuario = $id";
        return $this->db->query($sql);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM transfer_usuarios_sistema WHERE id_usuario = $id";
        return $this->db->query($sql);
    }
}
?>
