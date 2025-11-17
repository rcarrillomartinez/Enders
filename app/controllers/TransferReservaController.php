<?php
namespace app\controllers;

use app\core\Controller;
use app\models\TransferReserva;
use app\models\Auth;

class TransferReservaController extends Controller {
    private $model;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->model = new TransferReserva($pdo);
    }

    // --- Mostrar detalles de reserva ---
    public function show($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        $user = Auth::getCurrentUser();

        // Obtener la reserva con JOINs para nombres y descripciones
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
        $reserva = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$reserva) {
            die('Reserva no encontrada');
        }

        // Validar acceso: admin puede ver todo, usuario solo su reserva
        if ($user['user_type'] !== 'admin' && $reserva['email_cliente'] != $user['user_email']) {
            die('Acceso denegado');
        }

        // Renderizar vista directamente pasando el email
        $this->view('TransferReservaDetailView', [
            'reserva' => $reserva,
            'cliente_email' => $reserva['email_cliente']
        ]);
    }

    // --- Mostrar formulario de creación ---
    public function create() {
        if (!Auth::isLoggedIn()) {
            die('Acceso denegado');
        }

        $user = Auth::getCurrentUser();
        // Permitir admin y viajero
        if (!in_array($user['user_type'], ['admin','viajero'])) {
            die('Acceso denegado. Solo viajeros o admin pueden crear reservas.');
        }

        // Traer hoteles
        $stmt = $this->pdo->query("SELECT nombre_hotel FROM tranfer_hotel");
        $hoteles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Traer vehículos
        $stmt = $this->pdo->query("SELECT descripcion, capacidad FROM transfer_vehiculo");
        $vehiculos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('TransferReservaCreateView', [
            'user' => $user,
            'hoteles' => $hoteles,
            'vehiculos' => $vehiculos
        ]);
    }

    // --- Guardar nueva reserva ---
    public function store() {
        if (!Auth::isLoggedIn()) {
            die('Acceso denegado');
        }

        $user = Auth::getCurrentUser();
        if (!in_array($user['user_type'], ['admin','viajero'])) {
            die('Acceso denegado. Solo viajeros o admin pueden crear reservas.');
        }

        $data = $_POST;

        // Validar tipo de reserva
        if (empty($data['tipo_reserva'])) {
            die('Debe seleccionar un tipo de reserva.');
        }
        $tipoReservaId = (int)$data['tipo_reserva'];

        // Validar hotel
        if (empty($data['nombre_hotel'])) {
            die('Debe seleccionar un hotel.');
        }
        $stmt = $this->pdo->prepare("SELECT id_hotel FROM tranfer_hotel WHERE nombre_hotel = :nombre");
        $stmt->execute([':nombre' => $data['nombre_hotel']]);
        $hotel = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$hotel) die('Hotel no encontrado.');
        $hotelId = $hotel['id_hotel'];

        // Validar vehículo
        if (empty($data['vehiculo_descripcion'])) {
            die('Debe seleccionar un vehículo.');
        }
        $stmt = $this->pdo->prepare("SELECT id_vehiculo, capacidad FROM transfer_vehiculo WHERE descripcion = :desc");
        $stmt->execute([':desc' => $data['vehiculo_descripcion']]);
        $vehiculo = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$vehiculo) die('Vehículo no encontrado.');
        $vehiculoId = $vehiculo['id_vehiculo'];

        // Validar o crear viajero
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_viajeros WHERE email = :email");
        $stmt->execute([':email' => $data['email_cliente']]);
        $viajero = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$viajero) {
            $pwHash = password_hash($data['password'] ?? '1234', PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("
                INSERT INTO transfer_viajeros 
                (nombre, apellido1, apellido2, email, password, direccion, codigoPostal, ciudad, pais)
                VALUES (:nombre, :apellido1, :apellido2, :email, :password, :direccion, :codigoPostal, :ciudad, :pais)
            ");
            $stmt->execute([
                ':nombre' => $data['nombre_cliente'] ?? '',
                ':apellido1' => $data['apellido1_cliente'] ?? '',
                ':apellido2' => $data['apellido2_cliente'] ?? '',
                ':email' => $data['email_cliente'],
                ':password' => $pwHash,
                ':direccion' => $data['direccion'] ?? '',
                ':codigoPostal' => $data['codigoPostal'] ?? '',
                ':ciudad' => $data['ciudad'] ?? '',
                ':pais' => $data['pais'] ?? ''
            ]);
            $viajeroId = $this->pdo->lastInsertId();
        } else {
            $viajeroId = $viajero['id_viajero'];
        }

        // Generar localizador único
        $localizador = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        // Insertar reserva
        $stmt = $this->pdo->prepare("
            INSERT INTO transfer_reservas
            (localizador, id_hotel, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion,
            id_destino, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada,
            hora_vuelo_salida, fecha_vuelo_salida, num_viajeros, id_vehiculo)
            VALUES
            (:localizador, :id_hotel, :id_tipo_reserva, :email_cliente, NOW(), NOW(),
            :id_destino, :fecha_entrada, :hora_entrada, :numero_vuelo_entrada, :origen_vuelo_entrada,
            :hora_vuelo_salida, :fecha_vuelo_salida, :num_viajeros, :id_vehiculo)
        ");

        $stmt->execute([
            ':localizador' => $localizador,
            ':id_hotel' => $hotelId,
            ':id_tipo_reserva' => $tipoReservaId,
            ':email_cliente' => $data['email_cliente'],  
            ':id_destino' => $hotelId,
            ':fecha_entrada' => !empty($data['fecha_llegada']) ? $data['fecha_llegada'] : null,
            ':hora_entrada' => !empty($data['hora_llegada']) ? $data['hora_llegada'] : null,
            ':numero_vuelo_entrada' => $data['vuelo_llegada'] ?? null,
            ':origen_vuelo_entrada' => $data['origen_llegada'] ?? null,
            ':hora_vuelo_salida' => !empty($data['hora_salida']) ? $data['hora_salida'] : null,
            ':fecha_vuelo_salida' => !empty($data['fecha_salida']) ? $data['fecha_salida'] : null,
            ':num_viajeros' => $data['num_viajeros'] ?? 1,
            ':id_vehiculo' => $vehiculoId
        ]);

        header('Location: ?action=showReserva&id=' . $this->pdo->lastInsertId());
    }
}
