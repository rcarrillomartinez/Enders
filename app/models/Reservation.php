<?php
namespace app\models;

use app\core\Database;

class Reservation {
    private $pdo;
    public function __construct(){ $this->pdo = Database::get(); }

    public function find(int $id) {
        $stmt = $this->pdo->prepare('SELECT r.*, h.usuario as hotel_usuario, v.email as cliente_email, v.nombre as cliente_nombre FROM transfer_reservas r LEFT JOIN tranfer_hotel h ON r.id_hotel = h.id_hotel LEFT JOIN transfer_viajeros v ON r.email_cliente = v.id_viajero WHERE r.id_reserva = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function byUser(int $userId): array {
        $stmt = $this->pdo->prepare('SELECT * FROM transfer_reservas WHERE email_cliente = ? ORDER BY fecha_reserva DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function all(int $limit = 100): array {
        $stmt = $this->pdo->query('SELECT * FROM transfer_reservas ORDER BY fecha_reserva DESC LIMIT '.(int)$limit);
        return $stmt->fetchAll();
    }

    public function createFromAdmin(array $data) {
        $locator = $this->generateLocator();
        $stmt = $this->pdo->prepare('INSERT INTO transfer_reservas (localizador, id_hotel, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion, id_destino, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada, fecha_vuelo_salida, hora_vuelo_salida, num_viajeros, id_vehiculo) VALUES (?,?,?,?,NOW(),NOW(),?,?,?,?,?,?,?,?,?)');
        $res = $stmt->execute([
            $locator,
            $data['id_hotel'] ?? null,
            $data['id_tipo_reserva'] ?? 1,
            $data['cliente_id'] ?? null,
            $data['id_destino'] ?? null,
            $data['fecha_entrada'] ?? null,
            $data['hora_entrada'] ?? null,
            $data['numero_vuelo_entrada'] ?? null,
            $data['origen_vuelo_entrada'] ?? null,
            $data['fecha_vuelo_salida'] ?? null,
            $data['hora_vuelo_salida'] ?? null,
            $data['num_viajeros'] ?? 1,
            $data['id_vehiculo'] ?? null
        ]);
        if ($res) {
            if (!empty($data['cliente_email'])) $this->sendMail($data['cliente_email'], $locator);
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    private function generateLocator(): string {
        do {
            $loc = strtoupper(substr(bin2hex(random_bytes(4)),0,8));
            $stmt = $this->pdo->prepare('SELECT id_reserva FROM transfer_reservas WHERE localizador = ?');
            $stmt->execute([$loc]);
            $exists = $stmt->fetch();
        } while ($exists);
        return $loc;
    }

    private function sendMail(string $to, string $locator) {
        if (!$to) return;
        $subject = "Reserva - Localizador $locator";
        $message = "Su reserva ha sido creada. Localizador: $locator";
        $headers = 'From: '.MAIL_FROM."\r\n";
        @mail($to, $subject, $message, $headers);
    }

    public function byRange(string $view, string $date): array {
        $start = $date; $end = $date;
        if ($view == 'week') {
            $start = date('Y-m-d', strtotime($date . ' -3 days'));
            $end   = date('Y-m-d', strtotime($date . ' +3 days'));
        } elseif ($view == 'month') {
            $start = date('Y-m-01', strtotime($date));
            $end   = date('Y-m-t', strtotime($date));
        }
        $stmt = $this->pdo->prepare('SELECT * FROM transfer_reservas WHERE (fecha_entrada BETWEEN ? AND ?) OR (fecha_vuelo_salida BETWEEN ? AND ?) ORDER BY fecha_entrada');
        $stmt->execute([$start,$end,$start,$end]);
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data) {
        $stmt = $this->pdo->prepare('UPDATE transfer_reservas SET id_hotel=?, id_destino=?, fecha_entrada=?, hora_entrada=?, fecha_vuelo_salida=?, hora_vuelo_salida=?, num_viajeros=?, id_vehiculo=?, numero_vuelo_entrada=?, origen_vuelo_entrada=?, fecha_modificacion = NOW() WHERE id_reserva = ?');
        return $stmt->execute([
            $data['id_hotel'] ?? null,
            $data['id_destino'] ?? null,
            $data['fecha_entrada'] ?? null,
            $data['hora_entrada'] ?? null,
            $data['fecha_vuelo_salida'] ?? null,
            $data['hora_vuelo_salida'] ?? null,
            $data['num_viajeros'] ?? 1,
            $data['id_vehiculo'] ?? null,
            $data['numero_vuelo_entrada'] ?? null,
            $data['origen_vuelo_entrada'] ?? null,
            $id
        ]);
    }

    public function cancel(int $id) {
        $stmt = $this->pdo->prepare('DELETE FROM transfer_reservas WHERE id_reserva = ?');
        return $stmt->execute([$id]);
    }
}
