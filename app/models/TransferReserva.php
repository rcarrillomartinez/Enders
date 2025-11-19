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
        if ($filterByEmail) {
            $stmt = $this->pdo->prepare('SELECT * FROM transfer_reservas WHERE email_cliente = :email');
            $stmt->execute([':email' => $filterByEmail]);
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->query('SELECT * FROM transfer_reservas');
            return $stmt->fetchAll();
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
        $sql = "INSERT INTO transfer_reservas 
            (id_tipo_reserva, id_hotel, id_vehiculo, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada,
            fecha_vuelo_salida, hora_vuelo_salida, numero_vuelo_salida, hora_recogida_salida, num_viajeros,
            estado, email_cliente, nombre_cliente, apellido1_cliente, apellido2_cliente, localizador, fecha_reserva)
            VALUES
            (:id_tipo_reserva, :id_hotel, :id_vehiculo, :fecha_entrada, :hora_entrada, :numero_vuelo_entrada, :origen_vuelo_entrada,
            :fecha_vuelo_salida, :hora_vuelo_salida, :numero_vuelo_salida, :hora_recogida_salida, :num_viajeros,
            :estado, :email_cliente, :nombre_cliente, :apellido1_cliente, :apellido2_cliente, :localizador, CURDATE())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_tipo_reserva' => $data['tipo_reserva'] ?? null,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':id_vehiculo' => $data['id_vehiculo'] ?? null,
            ':fecha_entrada' => $data['fecha_llegada'] ?? null,
            ':hora_entrada' => $data['hora_llegada'] ?? null,
            ':numero_vuelo_entrada' => $data['vuelo_llegada'] ?? null,
            ':origen_vuelo_entrada' => $data['origen_llegada'] ?? null,
            ':fecha_vuelo_salida' => $data['fecha_salida'] ?? null,
            ':hora_vuelo_salida' => $data['hora_salida'] ?? null,
            ':numero_vuelo_salida' => $data['vuelo_salida'] ?? null,
            ':hora_recogida_salida' => $data['hora_recogida'] ?? null,
            ':num_viajeros' => $data['num_viajeros'] ?? 1,
            ':estado' => $data['estado'] ?? 'pendiente',
            ':email_cliente' => $data['email_cliente'] ?? null,
            ':nombre_cliente' => $data['nombre_cliente'] ?? null,
            ':apellido1_cliente' => $data['apellido1_cliente'] ?? null,
            ':apellido2_cliente' => $data['apellido2_cliente'] ?? null,
            ':localizador' => 'TR-' . strtoupper(substr(md5(uniqid()), 0, 6))
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE transfer_reservas SET 
                    id_transfer = :id_transfer,
                    id_hotel = :id_hotel,
                    tipo_reserva = :tipo_reserva,
                    fecha_llegada = :fecha_llegada,
                    hora_llegada = :hora_llegada,
                    vuelo_llegada = :vuelo_llegada,
                    origen_llegada = :origen_llegada,
                    fecha_salida = :fecha_salida,
                    hora_salida = :hora_salida,
                    vuelo_salida = :vuelo_salida,
                    hora_recogida = :hora_recogida,
                    num_pasajeros = :num_pasajeros,
                    estado = :estado,
                    email_cliente = :email_cliente
                WHERE id_reserva = :id_reserva";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_transfer' => $data['id_vehiculo'] ?? null,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':tipo_reserva' => $data['tipo_reserva'] ?? null,
            ':fecha_llegada' => $data['fecha_llegada'] ?? null,
            ':hora_llegada' => $data['hora_llegada'] ?? null,
            ':vuelo_llegada' => $data['vuelo_llegada'] ?? null,
            ':origen_llegada' => $data['origen_llegada'] ?? null,
            ':fecha_salida' => $data['fecha_salida'] ?? null,
            ':hora_salida' => $data['hora_salida'] ?? null,
            ':vuelo_salida' => $data['vuelo_salida'] ?? null,
            ':hora_recogida' => $data['hora_recogida'] ?? null,
            ':num_pasajeros' => $data['num_viajeros'] ?? 1,
            ':estado' => $data['estado'] ?? 'pendiente',
            ':email_cliente' => $data['email_cliente'] ?? null,
            ':id_reserva' => $id
        ]);

        return $stmt->rowCount() > 0;
    }
}
