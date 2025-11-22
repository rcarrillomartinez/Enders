<?php

// Clase TransferReserva: Gestiona las operaciones CRUD para las reservas de transfer.
class TransferReserva {
    private $pdo;
    private $table = 'transfer_reservas';

    /**
     * Constructor de la clase.
     * @param PDO $pdo Instancia de la conexión a la base de datos.
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene datos relacionados de otras tablas (hoteles, vehículos, destinos, tipos de reserva).
     * @param string $tableName El nombre de la tabla de la que se quieren obtener datos.
     * @return array Un array asociativo con los datos de la tabla.
     * @throws InvalidArgumentException Si el nombre de la tabla no está permitido.
     * @throws Exception Si ocurre un error al obtener los datos.
     */
    public function getRelatedData(string $tableName): array {
        // Tablas permitidas para obtener datos relacionados.
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

    /**
     * Obtiene todas las reservas, opcionalmente filtradas por email de cliente.
     * @param string|null $filterByEmail Email del cliente para filtrar las reservas.
     * @return array Un array de arrays asociativos con los datos de las reservas.
     */
    public function getAll($filterByEmail = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($filterByEmail) {
            $sql .= " WHERE email_cliente = :email";
            // Añade una cláusula WHERE si se proporciona un email para filtrar.
        }
        $sql .= " ORDER BY fecha_entrada DESC";
        
        $stmt = $this->pdo->prepare($sql);
        if ($filterByEmail) {
            $stmt->bindParam(':email', $filterByEmail);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una reserva por su ID.
     * @param int $id El ID de la reserva.
     * @return array|false Un array asociativo con los datos de la reserva o false si no se encuentra.
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_reserva = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // Devuelve la primera fila como un array asociativo.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $tipo = $data['tipo_reserva'] ?? '0';
        $isLlegadaRequired = in_array($tipo, ['1', '3']);
        $isSalidaRequired = in_array($tipo, ['2', '3']);
        // Validación básica de campos requeridos según el tipo de reserva.
        if (empty($data['email_cliente']) || ($isLlegadaRequired && empty($data['fecha_llegada'])) || ($isSalidaRequired && empty($data['fecha_salida']))) {
            throw new Exception("Faltan campos requeridos. Por favor, complete el email y las fechas correspondientes al tipo de reserva.");
        }

        // Genera un localizador único para la reserva.
        $localizador = 'TR-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        $sql = "INSERT INTO {$this->table} (
                    localizador, id_hotel, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion,
                    fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada,
                    fecha_vuelo_salida, hora_partida, num_viajeros, id_vehiculo, estado, nombre_cliente,
                    apellido1_cliente, apellido2_cliente
                ) VALUES (
                    :localizador, :id_hotel, :tipo_reserva, :email_cliente, NOW(), NOW(),
                    :fecha_entrada, :hora_entrada, :numero_vuelo_entrada, :origen_vuelo_entrada,
                    :fecha_vuelo_salida, :hora_partida, :num_viajeros, :id_vehiculo, :estado, :nombre_cliente, :apellido1_cliente,
                    :apellido2_cliente
                )";
        
        $stmt = $this->pdo->prepare($sql);

        // Determina el estado inicial de la reserva, permitiendo al admin establecerlo.
        $estado = (Auth::getCurrentUser()['user_type'] === 'admin' && isset($data['estado'])) ? $data['estado'] : 'pendiente';

        $fecha_entrada = !empty($data['fecha_llegada']) ? $data['fecha_llegada'] : ($data['fecha_salida'] ?? date('Y-m-d'));
        $fecha_salida = !empty($data['fecha_salida']) ? $data['fecha_salida'] : $fecha_entrada;

        $stmt->execute([
            ':localizador' => $localizador,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':tipo_reserva' => $data['tipo_reserva'] ?? null,
            ':email_cliente' => $data['email_cliente'],
            ':fecha_entrada' => $fecha_entrada,
            ':hora_entrada' => !empty($data['hora_llegada']) ? $data['hora_llegada'] : '00:00:00',
            ':numero_vuelo_entrada' => $data['vuelo_llegada'] ?? 'N/A',
            ':origen_vuelo_entrada' => $data['origen_llegada'] ?? 'N/A',
            ':fecha_vuelo_salida' => $fecha_salida,
            ':hora_partida' => !empty($data['hora_partida']) ? $data['hora_partida'] : '00:00:00',
            ':num_viajeros' => $data['num_viajeros'] ?? 1,
            ':id_vehiculo' => $data['id_vehiculo'] ?? null,
            ':estado' => $estado,
            ':nombre_cliente' => $data['nombre_cliente'] ?? '',
            ':apellido1_cliente' => $data['apellido1_cliente'] ?? null,
            ':apellido2_cliente' => $data['apellido2_cliente'] ?? null,
        ]);

        // Devuelve el ID de la última fila insertada.
        return $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una reserva existente.
     * @param int $id El ID de la reserva a actualizar.
     * @param array $data Un array asociativo con los nuevos datos de la reserva.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     * @throws Exception Si el ID de la reserva no es válido.
     */
    public function update($id, $data) {
        // Verifica si el ID de la reserva es válido.
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
                    estado = :estado, nombre_cliente = :nombre_cliente,
                    apellido1_cliente = :apellido1_cliente, apellido2_cliente = :apellido2_cliente
                WHERE id_reserva = :id_reserva";

        $stmt = $this->pdo->prepare($sql);

        // Determina el estado de la reserva, permitiendo al admin establecerlo o manteniendo el actual.
        $estado = (Auth::getCurrentUser()['user_type'] === 'admin' && isset($data['estado'])) ? $data['estado'] : $this->getById($id)['estado'];

        $fecha_entrada = !empty($data['fecha_llegada']) ? $data['fecha_llegada'] : ($data['fecha_salida'] ?? date('Y-m-d'));
        $fecha_salida = !empty($data['fecha_salida']) ? $data['fecha_salida'] : $fecha_entrada;

        return $stmt->execute([
            ':id_reserva' => $id,
            ':id_hotel' => $data['id_hotel'] ?? null,
            ':tipo_reserva' => $data['tipo_reserva'] ?? null,
            ':email_cliente' => $data['email_cliente'],
            ':fecha_entrada' => $fecha_entrada,
            ':hora_entrada' => !empty($data['hora_llegada']) ? $data['hora_llegada'] : '00:00:00',
            ':numero_vuelo_entrada' => $data['vuelo_llegada'] ?? 'N/A',
            ':origen_vuelo_entrada' => $data['origen_llegada'] ?? 'N/A',
            ':fecha_vuelo_salida' => $fecha_salida,
            ':hora_partida' => !empty($data['hora_partida']) ? $data['hora_partida'] : '00:00:00',
            ':num_viajeros' => $data['num_viajeros'] ?? 1, // Default to 1 traveler
            ':id_vehiculo' => $data['id_vehiculo'] ?? null,
            ':estado' => $estado,
            ':nombre_cliente' => $data['nombre_cliente'] ?? '',
            ':apellido1_cliente' => $data['apellido1_cliente'] ?? null,
            ':apellido2_cliente' => $data['apellido2_cliente'] ?? null,
        ]);
    }

    /**
     * Elimina una reserva por su ID.
     * @param int $id El ID de la reserva a eliminar.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_reserva = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Cuenta el número total de reservas.
     * @return int El número total de reservas.
     */
    public function count() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}