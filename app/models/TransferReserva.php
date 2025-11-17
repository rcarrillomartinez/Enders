<?php
require_once __DIR__ . '/Auth.php';

class TransferReserva {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // -----------------------------
    // Métodos para selectores dinámicos
    // -----------------------------

    public function getHoteles(): array {
        $stmt = $this->db->query("SELECT id_hotel, nombre_hotel FROM tranfer_hotel ORDER BY nombre_hotel");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getViajeros(): array {
        $stmt = $this->db->query("SELECT id_viajero, nombre, apellido1, apellido2 FROM transfer_viajeros ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTiposReserva(): array {
        return [
            ['id' => 1, 'nombre' => 'Aeropuerto a Hotel'],
            ['id' => 2, 'nombre' => 'Hotel a Aeropuerto'],
            ['id' => 3, 'nombre' => 'Ida y Vuelta']
        ];
    }

    // -----------------------------
    // CRUD de Reservas
    // -----------------------------

    public function getAll(?int $userId = null, ?string $userType = null, int $month = null, int $year = null): array {
        $sql = "SELECT r.*, h.nombre_hotel, v.nombre, v.apellido1, v.apellido2 
                FROM transfer_reservas r
                LEFT JOIN tranfer_hotel h ON r.id_hotel = h.id_hotel
                LEFT JOIN transfer_viajeros v ON r.id_viajero = v.id_viajero
                WHERE 1=1";

        $params = [];
        if ($userType === 'viajero' && $userId) {
            $sql .= " AND r.id_viajero = :id_viajero";
            $params[':id_viajero'] = $userId;
        }
        if ($month && $year) {
            $sql .= " AND MONTH(r.fecha_entrada) = :month AND YEAR(r.fecha_entrada) = :year";
            $params[':month'] = $month;
            $params[':year'] = $year;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT r.*, h.nombre_hotel, v.nombre, v.apellido1, v.apellido2
                                    FROM transfer_reservas r
                                    LEFT JOIN tranfer_hotel h ON r.id_hotel = h.id_hotel
                                    LEFT JOIN transfer_viajeros v ON r.id_viajero = v.id_viajero
                                    WHERE r.id_reserva = :id");
        $stmt->execute([':id' => $id]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
        return $reserva ?: null;
    }

    public function create(array $data, ?int $idViajero = null): array {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO transfer_reservas 
                (localizador, id_tipo_reserva, num_viajeros, id_hotel, id_viajero, email_cliente,
                 fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada,
                 fecha_vuelo_salida, hora_vuelo_salida, hora_partida, numero_vuelo_salida, estado)
                VALUES
                (:localizador, :id_tipo_reserva, :num_viajeros, :id_hotel, :id_viajero, :email_cliente,
                 :fecha_entrada, :hora_entrada, :numero_vuelo_entrada, :origen_vuelo_entrada,
                 :fecha_vuelo_salida, :hora_vuelo_salida, :hora_partida, :numero_vuelo_salida, 'Pendiente')
            ");

            $localizador = 'LOC' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $stmt->execute([
                ':localizador' => $localizador,
                ':id_tipo_reserva' => $data['id_tipo_reserva'],
                ':num_viajeros' => $data['num_viajeros'],
                ':id_hotel' => $data['id_hotel'],
                ':id_viajero' => $idViajero ?? $data['id_viajero'] ?? null,
                ':email_cliente' => $data['email_cliente'] ?? '',
                ':fecha_entrada' => $data['fecha_entrada'] ?? null,
                ':hora_entrada' => $data['hora_entrada'] ?? null,
                ':numero_vuelo_entrada' => $data['numero_vuelo_entrada'] ?? null,
                ':origen_vuelo_entrada' => $data['origen_vuelo_entrada'] ?? null,
                ':fecha_vuelo_salida' => $data['fecha_vuelo_salida'] ?? null,
                ':hora_vuelo_salida' => $data['hora_vuelo_salida'] ?? null,
                ':hora_partida' => $data['hora_partida'] ?? null,
                ':numero_vuelo_salida' => $data['numero_vuelo_salida'] ?? null
            ]);

            return ['success' => true, 'message' => 'Reserva creada exitosamente', 'id_reserva' => $this->db->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Error crear reserva: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al crear reserva'];
        }
    }

    public function update(array $data): array {
        try {
            $stmt = $this->db->prepare("
                UPDATE transfer_reservas SET
                id_tipo_reserva = :id_tipo_reserva,
                num_viajeros = :num_viajeros,
                id_hotel = :id_hotel,
                id_viajero = :id_viajero,
                email_cliente = :email_cliente,
                fecha_entrada = :fecha_entrada,
                hora_entrada = :hora_entrada,
                numero_vuelo_entrada = :numero_vuelo_entrada,
                origen_vuelo_entrada = :origen_vuelo_entrada,
                fecha_vuelo_salida = :fecha_vuelo_salida,
                hora_vuelo_salida = :hora_vuelo_salida,
                hora_partida = :hora_partida,
                numero_vuelo_salida = :numero_vuelo_salida,
                estado = :estado
                WHERE id_reserva = :id_reserva
            ");

            $stmt->execute([
                ':id_reserva' => $data['id_reserva'],
                ':id_tipo_reserva' => $data['id_tipo_reserva'],
                ':num_viajeros' => $data['num_viajeros'],
                ':id_hotel' => $data['id_hotel'],
                ':id_viajero' => $data['id_viajero'],
                ':email_cliente' => $data['email_cliente'] ?? '',
                ':fecha_entrada' => $data['fecha_entrada'] ?? null,
                ':hora_entrada' => $data['hora_entrada'] ?? null,
                ':numero_vuelo_entrada' => $data['numero_vuelo_entrada'] ?? null,
                ':origen_vuelo_entrada' => $data['origen_vuelo_entrada'] ?? null,
                ':fecha_vuelo_salida' => $data['fecha_vuelo_salida'] ?? null,
                ':hora_vuelo_salida' => $data['hora_vuelo_salida'] ?? null,
                ':hora_partida' => $data['hora_partida'] ?? null,
                ':numero_vuelo_salida' => $data['numero_vuelo_salida'] ?? null,
                ':estado' => $data['estado'] ?? 'Pendiente'
            ]);

            return ['success' => true, 'message' => 'Reserva actualizada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error actualizar reserva: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar reserva'];
        }
    }

    public function cancel(int $id): array {
        try {
            $stmt = $this->db->prepare("UPDATE transfer_reservas SET estado='Cancelada' WHERE id_reserva = :id");
            $stmt->execute([':id' => $id]);
            return ['success' => true, 'message' => 'Reserva cancelada exitosamente'];
        } catch (PDOException $e) {
            error_log("Error cancelar reserva: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al cancelar reserva'];
        }
    }
}
