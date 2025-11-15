<?php
require_once 'DBConnection.php';
class Reserva {
    private $db;
    public function __construct(DBConnection $db) { $this->db = $db; }

    public function crear($data) {
        $localizador = uniqid("RES-");
        $stmt = $this->db->prepare("INSERT INTO transfer_reservas (localizador, id_hotel, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion, id_destino, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada, hora_vuelo_salida, fecha_vuelo_salida, num_viajeros, id_vehiculo, id_usuario_sistema) VALUES (:localizador, :id_hotel, :id_tipo_reserva, :email_cliente, NOW(), NOW(), :id_destino, :fecha_entrada, :hora_entrada, :numero_vuelo_entrada, :origen_vuelo_entrada, :hora_vuelo_salida, :fecha_vuelo_salida, :num_viajeros, :id_vehiculo, :id_usuario_sistema)");

        return $stmt->execute([
            ':localizador' => $localizador,
            ':id_hotel' => $data['id_hotel'],
            ':id_tipo_reserva' => $data['id_tipo_reserva'],
            ':email_cliente' => $data['email_cliente'],
            ':id_destino' => $data['id_destino'],
            ':fecha_entrada' => $data['fecha_entrada'],
            ':hora_entrada' => $data['hora_entrada'],
            ':numero_vuelo_entrada' => $data['numero_vuelo_entrada'],
            ':origen_vuelo_entrada' => $data['origen_vuelo_entrada'],
            ':hora_vuelo_salida' => $data['hora_vuelo_salida'],
            ':fecha_vuelo_salida' => $data['fecha_vuelo_salida'],
            ':num_viajeros' => $data['num_viajeros'],
            ':id_vehiculo' => $data['id_vehiculo'],
            ':id_usuario_sistema' => $data['id_usuario_sistema']
        ]);
    }

    public function modificar($id, $data) {
        $set = [];
        foreach ($data as $key => $val) {
            $val = $this->db->escape($val);
            $set[] = "$key = '$val'";
        }
        $sql = "UPDATE transfer_reservas SET " . implode(", ", $set) . ", fecha_modificacion=NOW() WHERE id_reserva = $id";
        return $this->db->query($sql);
    }

    public function cancelar($id) {
        $sql = "DELETE FROM transfer_reservas WHERE id_reserva = $id";
        return $this->db->query($sql);
    }

    public function listarPorUsuario($id_usuario) {
        $sql = "SELECT * FROM transfer_reservas WHERE id_usuario_sistema = $id_usuario";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function ver($id) {
        $sql = "SELECT * FROM transfer_reservas WHERE id_reserva = $id";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_assoc() : null;
    }
}
?>
