<?php

class TransferReserva {
    private $pdo;
    private $table = 'transfer_reservas';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getRelatedData(string $tableName): array {
        $allowedTables = ['tranfer_hotel', 'transfer_vehiculo', 'destinos', 'tipo_reserva'];
        if (!in_array($tableName, $allowedTables)) {
            throw new InvalidArgumentException("Table '{$tableName}' is not allowed.");
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$tableName}");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching data from table '{$tableName}': " . $e->getMessage());
        }
    }

    public function getAll($filterByEmail = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($filterByEmail) {
            $sql .= " WHERE email_cliente = :email";
        }
        $sql .= " ORDER BY fecha_entrada DESC";
        
        $stmt = $this->pdo->prepare($sql);
        if ($filterByEmail) {
            $stmt->bindParam(':email', $filterByEmail);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_reserva = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $tipo = $data['tipo_reserva'] ?? '0';
        $isLlegadaRequired = in_array($tipo, ['1', '3']);
        $isSalidaRequired = in_array($tipo, ['2', '3']);
        if (empty($data['email_cliente']) || ($isLlegadaRequired && empty($data['fecha_llegada'])) || ($isSalidaRequired && empty($data['fecha_salida']))) {
            throw new Exception("Faltan campos requeridos. Por favor, complete el email y las fechas correspondientes al tipo de reserva.");
        }

        $localizador = 'TR-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        $sql = "INSERT INTO {$this->table} (
                    localizador, id_hotel, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion,
                    id_destino, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada,
                    fecha_vuelo_salida, hora_partida, num_viajeros, id_vehiculo, estado, nombre_cliente
                ) VALUES (
                    :localizador, :id_hotel, :tipo_reserva, :email_cliente, NOW(), NOW(),
                    1, :fecha_entrada, :hora_entrada, :numero_vuelo_entrada, :origen_vuelo_entrada,
                    :fecha_vuelo_salida, :hora_partida, :num_viajeros, :id_vehiculo, :estado, :nombre_cliente
                )";
        
        $stmt = $this->pdo->prepare($sql);

        $estado = (Auth::getCurrentUser()['user_type'] === 'admin' && isset($data['estado'])) ? $data['estado'] : 'pendiente';

        $stmt->execute([
            ':localizador' => $localizador,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':tipo_reserva' => $data['tipo_reserva'] ?? null,
            ':email_cliente' => $data['email_cliente'],
            ':fecha_entrada' => $data['fecha_llegada'] ?? null,
            ':hora_entrada' => $data['hora_llegada'] ?? null,
            ':numero_vuelo_entrada' => $data['vuelo_llegada'] ?? null,
            ':origen_vuelo_entrada' => $data['origen_llegada'] ?? null,
            ':fecha_vuelo_salida' => $data['fecha_salida'] ?? null,
            ':hora_partida' => $data['hora_partida'] ?? null,
            ':num_viajeros' => $data['num_viajeros'] ?? 1,
            ':id_vehiculo' => $data['id_vehiculo'] ?? null,
            ':estado' => $estado,
            ':nombre_cliente' => $data['nombre_cliente'] ?? ''
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        if (empty($id)) {
            throw new Exception("ID de reserva no válido.");
        }

        $sql = "UPDATE {$this->table} SET
                    id_hotel = :id_hotel,
                    id_tipo_reserva = :tipo_reserva,
                    email_cliente = :email_cliente,
                    fecha_modificacion = NOW(),
                    fecha_entrada = :fecha_entrada,
                    hora_entrada = :hora_entrada,
                    numero_vuelo_entrada = :numero_vuelo_entrada,
                    origen_vuelo_entrada = :origen_vuelo_entrada,
                    fecha_vuelo_salida = :fecha_vuelo_salida,
                    hora_partida = :hora_partida,
                    num_viajeros = :num_viajeros,
                    id_vehiculo = :id_vehiculo,
                    estado = :estado,
                    nombre_cliente = :nombre_cliente
                WHERE id_reserva = :id_reserva";

        $stmt = $this->pdo->prepare($sql);

        $estado = (Auth::getCurrentUser()['user_type'] === 'admin' && isset($data['estado'])) ? $data['estado'] : $this->getById($id)['estado'];

        return $stmt->execute([
            ':id_reserva' => $id,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':tipo_reserva' => $data['tipo_reserva'] ?? null,
            ':email_cliente' => $data['email_cliente'],
            ':fecha_entrada' => $data['fecha_llegada'] ?? null,
            ':hora_entrada' => $data['hora_llegada'] ?? null,
            ':numero_vuelo_entrada' => $data['vuelo_llegada'] ?? null,
            ':origen_vuelo_entrada' => $data['origen_llegada'] ?? null,
            ':fecha_vuelo_salida' => $data['fecha_salida'] ?? null,
            ':hora_partida' => $data['hora_partida'] ?? null,
            ':num_viajeros' => $data['num_viajeros'] ?? 1,
            ':id_vehiculo' => $data['id_vehiculo'] ?? null,
            ':estado' => $estado,
            ':nombre_cliente' => $data['nombre_cliente'] ?? ''
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_reserva = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function count() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}