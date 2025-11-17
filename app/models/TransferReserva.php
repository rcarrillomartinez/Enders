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
    public function getAll($filterByEmail = null) {
        try {
            if ($filterByEmail) {
                $stmt = $this->pdo->prepare('SELECT * FROM transfer_reservas WHERE email_cliente = :email');
                $stmt->execute([':email' => $filterByEmail]);
                return $stmt->fetchAll();
            } else {
                $stmt = $this->pdo->query('SELECT * FROM transfer_reservas');
                return $stmt->fetchAll();
            }
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
        $sql = "INSERT INTO transfer_reservas (id_viajero, id_transfer, fecha_reserva, fecha_entrada, hora_entrada, num_pasajeros, estado, email_cliente, localizador)
                VALUES (:id_viajero, :id_transfer, CURDATE(), :fecha_entrada, :hora_entrada, :num_pasajeros, :estado, :email_cliente, :localizador)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_viajero' => $data['id_viajero'] ?? null,
                ':id_transfer' => $data['id_transfer'] ?? null,
                ':fecha_entrada' => $data['fecha_entrada'] ?? null,
                ':hora_entrada' => $data['hora_entrada'] ?? null,
                ':num_pasajeros' => $data['num_pasajeros'] ?? null,
                ':estado' => $data['estado'] ?? 'pendiente',
                ':email_cliente' => $data['email_cliente'] ?? null,
                ':localizador' => 'TR-' . strtoupper(substr(md5(uniqid()), 0, 6))
            ]);

            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new RuntimeException('Error creating transfer reserva: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE transfer_reservas SET 
                    fecha_entrada = :fecha_entrada, 
                    hora_entrada = :hora_entrada, 
                    num_pasajeros = :num_pasajeros, 
                    estado = :estado,
                    email_cliente = :email_cliente
                WHERE id_reserva = :id_reserva";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':fecha_entrada' => $data['fecha_entrada'] ?? null,
                ':hora_entrada' => $data['hora_entrada'] ?? null,
                ':num_pasajeros' => $data['num_pasajeros'] ?? null,
                ':estado' => $data['estado'] ?? 'pendiente',
                ':email_cliente' => $data['email_cliente'] ?? null,
                ':id_reserva' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new RuntimeException('Error updating transfer reserva: ' . $e->getMessage());
        }
    }

    public function delete(int $id) {
        $stmt = $this->pdo->prepare('DELETE FROM transfer_reservas WHERE id_reserva = :id');
        return $stmt->execute([':id' => $id]);
    }
}
