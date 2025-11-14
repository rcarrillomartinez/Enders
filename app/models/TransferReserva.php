<?php

class TransferReserva {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mira todas las reservas 
     * @return array
     */
    public function getAll() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM transfer_reservas');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new RuntimeException('Error fetching transfer reservas: ' . $e->getMessage());
        }
    }

    /**
     * Mira una reserva por ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM transfer_reservas WHERE id_reserva = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new RuntimeException('Error fetching transfer reserva: ' . $e->getMessage());
        }
    }

    /**
     * Cuenta el total de reservas
     * @return int
     */
    public function count() {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) as total FROM transfer_reservas');
            $result = $stmt->fetch();
            return $result['total'];
        } catch (PDOException $e) {
            throw new RuntimeException('Error counting transfer reservas: ' . $e->getMessage());
        }
    }

    /**
     * Insert a new transfer reservation
     * @param array $data
     * @return int inserted id
     */
    public function create(array $data) {
        $sql = "INSERT INTO transfer_reservas (id_viajero, id_transfer, fecha_reserva, fecha_partida, hora_partida, num_pasajeros, estado)
                VALUES (:id_viajero, :id_transfer, :fecha_reserva, :fecha_partida, :hora_partida, :num_pasajeros, :estado)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_viajero' => $data['id_viajero'] ?? null,
                ':id_transfer' => $data['id_transfer'] ?? null,
                ':fecha_reserva' => $data['fecha_reserva'] ?? null,
                ':fecha_partida' => $data['fecha_partida'] ?? null,
                ':hora_partida' => $data['hora_partida'] ?? null,
                ':num_pasajeros' => $data['num_pasajeros'] ?? null,
                ':estado' => $data['estado'] ?? null,
            ]);

            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new RuntimeException('Error creating transfer reserva: ' . $e->getMessage());
        }
    }
}
