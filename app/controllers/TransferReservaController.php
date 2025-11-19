<?php

// Incluye los modelos necesarios.
require_once __DIR__ . '/../models/TransferReserva.php';
require_once __DIR__ . '/../models/Auth.php';

// Clase TransferReservaController: Maneja las solicitudes relacionadas con las reservas de transfer.
class TransferReservaController {
    private $model;
    private $pdo;

    /**
     * Constructor de TransferReservaController.
     * @param PDO $pdo Instancia de la conexión a la base de datos.
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new TransferReserva($pdo);
    }

    /**
     * Muestra todas las reservas de transfer.
     */
    public function index() {
        // Verifica si el usuario ha iniciado sesión.
        if (!Auth::isLoggedIn()) {
            // Redirige al login si no está autenticado.
            header('Location: ?action=auth');
            exit();
        }

        try {
            $user = Auth::getCurrentUser();
            // Filtra las reservas por email si el usuario no es admin.
            $filterByEmail = null;
            
            if ($user['user_type'] !== 'admin') {
                $filterByEmail = $user['user_email'];
            }
            
            $reservas = $this->model->getAll($filterByEmail);
            $total = $this->model->count();
            // Incluye la vista del calendario de reservas.
            include __DIR__ . '/../views/TransferReservaView.php';
        } catch (Exception $e) {
            // Muestra un mensaje de error si algo sale mal.
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Muestra los detalles de una sola reserva de transfer.
     * @param int $id
     */
    public function show($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $reserva = $this->model->getById($id);
            // Verifica si la reserva existe.
            if (!$reserva) {
                // Muestra un error si la reserva no se encuentra.
                echo '<div style="color: red; padding: 20px;">';
                echo '<h2>Error</h2>';
                echo '<p>Reserva no encontrada.</p>';
                echo '</div>';
                return;
            }

            // Verifica si el usuario tiene permiso para ver esta reserva.
            $user = Auth::getCurrentUser();
            if ($user['user_type'] !== 'admin' && $reserva['email_cliente'] !== $user['user_email']) {
                echo '<div style="color: red; padding: 20px;">';
                echo '<h2>Error</h2>';
                echo '<p>No tiene acceso a esta reserva.</p>';
                echo '</div>';
                return;
            }

            // Incluye la vista de detalles de la reserva.
            include __DIR__ . '/../views/TransferReservaDetailView.php';
        } catch (Exception $e) {
            // Muestra un mensaje de error si algo sale mal.
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Muestra el formulario para crear una nueva reserva.
     * Redirige a la página de inicio de sesión si el usuario no está logueado.
     */
    public function create() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        $hoteles = $this->model->getRelatedData('tranfer_hotel');
        // Obtiene la lista de hoteles y vehículos para el formulario.
        $vehiculos = $this->model->getRelatedData('transfer_vehiculo');

        $data = [
            'hoteles' => $hoteles,
            'vehiculos' => $vehiculos,
        ];
        // Inicializa variables para errores y la acción del formulario.
        $errors = [];
        $formAction = '?action=store';

        // Incluye la vista del formulario de reserva.
        include __DIR__ . '/../views/TransferReservaFormView.php';
    }

    /**
     * Almacena una nueva reserva en la base de datos.
     * Redirige a la gestión de reservas con un mensaje de estado.
     * Muestra un error si la solicitud no es POST o el usuario no está logueado.
     */
    public function store() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=gestion_reservas');
            exit();
        }
        try {
            $this->model->create($_POST);
            // Redirige a la página de gestión con un mensaje de éxito.
            header('Location: ?action=gestion_reservas&status=created');
            exit();
        } catch (Exception $e) {
            // Muestra un mensaje de error si algo sale mal durante la creación.
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Muestra el formulario para editar una reserva existente.
     * @param int $id El ID de la reserva a editar.
     * Redirige a la gestión de reservas si la reserva no se encuentra o el usuario no tiene permisos.
     */
    public function edit($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }
        try {
            $reserva = $this->model->getById($id);
            // Verifica si la reserva existe.
            if (!$reserva) {
                header('Location: ?action=gestion_reservas&status=notfound');
                exit();
            }

            $user = Auth::getCurrentUser();
            if ($user['user_type'] !== 'admin' && $reserva['email_cliente'] !== $user['user_email']) {
                // Redirige si el usuario no tiene autorización.
                header('Location: ?action=gestion_reservas&status=unauthorized');
                exit();
            }

            $data = $reserva;
            // Obtiene datos relacionados para rellenar el formulario.
            $data['hoteles'] = $this->model->getRelatedData('tranfer_hotel');
            $data['vehiculos'] = $this->model->getRelatedData('transfer_vehiculo');
            // Inicializa variables para errores y la acción del formulario.
            $errors = [];
            $formAction = '?action=update';
            // Incluye la vista del formulario de reserva.
            include __DIR__ . '/../views/TransferReservaFormView.php';
        } catch (Exception $e) {
            // Redirige con un mensaje de error si algo sale mal.
            header('Location: ?action=gestion_reservas&status=error');
            exit();
        }
    }

    /**
     * Actualiza una reserva existente en la base de datos.
     * Redirige a la gestión de reservas con un mensaje de estado.
     * Muestra un error si la solicitud no es POST, el usuario no está logueado o no tiene permisos.
     */
    public function update() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=gestion_reservas');
            exit();
        }
        try {
            // Verifica si se proporcionó un ID de reserva.
            $id = $_POST['id_reserva'] ?? null;
            if (!$id) {
                throw new Exception("ID de reserva no proporcionado.");
            }

            // Verificación de autorización antes de la actualización.
            $reserva = $this->model->getById($id);
            $user = Auth::getCurrentUser();
            if ($user['user_type'] !== 'admin' && $reserva['email_cliente'] !== $user['user_email']) {
                throw new Exception("No tiene permiso para modificar esta reserva.");
            }

            $this->model->update($id, $_POST);
            // Redirige a la página de gestión con un mensaje de éxito.
            header('Location: ?action=gestion_reservas&status=updated');
            exit();
        } catch (Exception $e) {
            // Muestra un mensaje de error si algo sale mal durante la actualización.
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Elimina una reserva de la base de datos.
     * @param int $id El ID de la reserva a eliminar.
     * Redirige a la gestión de reservas con un mensaje de estado.
     * Muestra un error si el usuario no está logueado o no tiene permisos.
     */
    public function delete($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }
        try {
            $reserva = $this->model->getById($id);
            $user = Auth::getCurrentUser();
            if ($user['user_type'] !== 'admin' && $reserva['email_cliente'] !== $user['user_email']) {
                // Redirige si el usuario no tiene autorización.
                header('Location: ?action=gestion_reservas&status=unauthorized');
                exit();
            }

            $this->model->delete($id);
            // Redirige a la página de gestión con un mensaje de éxito.
            header('Location: ?action=gestion_reservas&status=deleted');
            exit();
        } catch (Exception $e) {
            // Redirige con un mensaje de error si algo sale mal.
            header('Location: ?action=gestion_reservas&status=error&msg=' . urlencode($e->getMessage()));
            exit();
        }
    }

    /**
     * Muestra la vista de gestión de reservas.
     * Redirige a la página de inicio de sesión si el usuario no está logueado.
     */
    public function gestion() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }
        try {
            $user = Auth::getCurrentUser();
            // Filtra las reservas por email si el usuario no es admin.
            $filterByEmail = ($user['user_type'] !== 'admin') ? $user['user_email'] : null;
            $reservas = $this->model->getAll($filterByEmail);
            // Incluye la vista de gestión de reservas.
            include __DIR__ . '/../views/GestionReservasView.php';
        } catch (Exception $e) {
            // Muestra un mensaje de error si algo sale mal.
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }
}
