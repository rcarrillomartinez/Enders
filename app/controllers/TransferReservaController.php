<?php
require_once __DIR__ . '/../core/Controller.php';

// Load all required models
require_once __DIR__ . '/../models/TransferReserva.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Hotel.php'; 
require_once __DIR__ . '/../models/Vehiculo.php';

class TransferReservaController extends Controller {
    private $model;
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new TransferReserva($pdo);
    }

    /* =======================================================
       LISTADO DE RESERVAS
    ======================================================= */
    public function index() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $user = Auth::getCurrentUser();
            $filterByEmail = ($user['user_type'] !== 'admin') ? $user['user_email'] : null;

            $reservas = $this->model->getAll($filterByEmail);
            $total = $this->model->count();

            include __DIR__ . '/../views/TransferReservaView.php';

        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;"><h2>Error</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    /* =======================================================
       MOSTRAR UNA RESERVA
    ======================================================= */
    public function show($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $reserva = $this->model->getById($id);

            if (!$reserva) {
                echo '<div style="color: red; padding: 20px;"><h2>Error</h2><p>Reserva no encontrada.</p></div>';
                return;
            }

            $user = Auth::getCurrentUser();

            if ($user['user_type'] !== 'admin' && $reserva['email_cliente'] !== $user['user_email']) {
                echo '<div style="color: red; padding: 20px;"><h2>Error</h2><p>No tiene acceso a esta reserva.</p></div>';
                return;
            }

            include __DIR__ . '/../views/TransferReservaDetailView.php';

        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;"><h2>Error</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    /* =======================================================
       FORMULARIO DE CREACIÓN
    ======================================================= */
    public function create() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $hotelModel = new Hotel();
            $vehiculoModel = new Vehiculo();

            $data = [
                "hoteles" => $hotelModel->all(),
                "vehiculos" => $vehiculoModel->all(),
                "data" => [],
                "formAction" => "?action=transfer_reserva_store"
            ];

            include __DIR__ . '/../views/TransferReservaFormView.php';

        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;"><h2>Error al cargar formulario</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    /* =======================================================
       GUARDAR RESERVA
    ======================================================= */
    public function store() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=gestion_reservas');
            exit();
        }

        try {
            // Copiar los datos del formulario
            $data = $_POST;

            // Si no es admin, asignar automáticamente el email del usuario logueado
            if (!Auth::isAdmin()) {
                $currentUser = Auth::getCurrentUser();
                $data['email_cliente'] = $currentUser['user_email'];
            }
            $this->model->create($data);
            header('Location: ?action=gestion_reservas&status=created');
            exit();
        } catch (Exception $e) {
            echo '<div style="color:red; padding:20px;"><h2>Error</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    /* =======================================================
       FORMULARIO DE EDICIÓN
    ======================================================= */
    public function edit($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $reserva = $this->model->getById($id);
            if (!$reserva) {
                header('Location: ?action=gestion_reservas&status=notfound');
                exit();
            }

            $hotelModel = new Hotel();
            $vehiculoModel = new Vehiculo();

            $data = [
                "data" => $reserva,
                "hoteles" => $hotelModel->all(),
                "vehiculos" => $vehiculoModel->all(),
                "formAction" => "?action=transfer_reserva_update"
            ];

            include __DIR__ . '/../views/TransferReservaFormView.php';

        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;"><h2>Error al cargar edición</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    /* =======================================================
       ACTUALIZAR RESERVA
    ======================================================= */
    public function update() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=gestion_reservas');
            exit();
        }

        try {
            $id = $_POST['id_reserva'] ?? null;
            if (!$id) throw new Exception("ID no proporcionado.");

            $data = $_POST;

            // Si no es admin, forzar email_cliente a ser el del usuario logueado
            if (!Auth::isAdmin()) {
                $currentUser = Auth::getCurrentUser();
                $data['email_cliente'] = $currentUser['user_email'];
            }

            $this->model->update($id, $data);

            header('Location: ?action=gestion_reservas&status=updated');
            exit();

        } catch (Exception $e) {
            echo '<div style="color:red; padding:20px;"><h2>Error</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    /* =======================================================
       BORRAR
    ======================================================= */
    public function delete($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $this->model->delete($id);
            header('Location: ?action=gestion_reservas&status=deleted');
            exit();

        } catch (Exception $e) {
            echo '<div style="color:red; padding:20px;"><h2>Error</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    /* =======================================================
       GESTIÓN
    ======================================================= */
    public function gestion() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $user = Auth::getCurrentUser();
            $filterByEmail = ($user['user_type'] !== 'admin') ? $user['user_email'] : null;

            $reservas = $this->model->getAll($filterByEmail);

            include __DIR__ . '/../views/GestionReservasView.php';

        } catch (Exception $e) {
            echo '<div style="color:red; padding:20px;"><h2>Error</h2><p>' 
                . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    }

    public function filterVehiculos() {
        $num_viajeros = isset($_GET['viajeros']) ? (int)$_GET['viajeros'] : 0;
        $vehiculoModel = new Vehiculo();

        try {
            $vehiculos = $vehiculoModel->getByCapacity($num_viajeros);
            header('Content-Type: application/json');
            echo json_encode($vehiculos);
            exit();
        } catch (Exception $e) {
            echo json_encode([]);
            exit();
        }
    }

}
