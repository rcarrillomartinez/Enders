<?php
namespace app\models;

use PDO;
use app\core\Database;

class TransferReserva {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM transfer_reservas ORDER BY fecha_reserva DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUser($email) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM transfer_reservas 
            WHERE email_cliente = :email 
            ORDER BY fecha_reserva DESC
        ");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, 
                h.nombre_hotel AS hotel_nombre,
                v.descripcion AS vehiculo_descripcion,
                t.descripcion AS tipo_reserva_descripcion
            FROM transfer_reservas r
            LEFT JOIN tranfer_hotel h ON r.id_hotel = h.id_hotel
            LEFT JOIN transfer_vehiculo v ON r.id_vehiculo = v.id_vehiculo
            LEFT JOIN transfer_tipo_reserva t ON r.id_tipo_reserva = t.id_tipo_reserva
            WHERE r.id_reserva = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO transfer_reservas 
            (id_hotel, id_tipo_reserva, email_cliente, fecha_reserva, fecha_entrada, hora_entrada, num_viajeros, id_vehiculo)
            VALUES 
            (:id_hotel, :id_tipo_reserva, :email_cliente, :fecha_reserva, :fecha_entrada, :hora_entrada, :num_viajeros, :id_vehiculo)
        ");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }
}
