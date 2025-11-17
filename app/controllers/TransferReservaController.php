<?php

require_once __DIR__ . '/../models/TransferReserva.php';
require_once __DIR__ . '/../models/Auth.php';

class TransferReservaController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new TransferReserva($pdo);
    }

    /**
     * Enseña todas las reservas de transfer
     */
    public function index() {
        // Check if user is logged in
        if (!Auth::isLoggedIn()) {
            // Redirect to login
            header('Location: ?action=auth');
            exit();
        }

        try {
            $user = Auth::getCurrentUser();
            $filterByEmail = null;
            
            // If not admin, filter by user email
            if ($user['user_type'] !== 'admin') {
                $filterByEmail = $user['user_email'];
            }
            
            $reservas = $this->model->getAll($filterByEmail);
            $total = $this->model->count();
            
            include __DIR__ . '/../views/TransferReservaView.php';
        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Enseña una sola reserva de transfer
     * @param int $id
     */
    public function show($id) {
        // Check if user is logged in
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }

        try {
            $reserva = $this->model->getById($id);
            
            if (!$reserva) {
                echo '<div style="color: red; padding: 20px;">';
                echo '<h2>Error</h2>';
                echo '<p>Reservation not found.</p>';
                echo '</div>';
                return;
            }

            // Check if user has access to this reservation
            $user = Auth::getCurrentUser();
            if ($user['user_type'] !== 'admin' && $reserva['email_cliente'] !== $user['user_email']) {
                echo '<div style="color: red; padding: 20px;">';
                echo '<h2>Error</h2>';
                echo '<p>No tiene acceso a esta reserva.</p>';
                echo '</div>';
                return;
            }

            include __DIR__ . '/../views/TransferReservaDetailView.php';
        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

    public function create() {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }
        // Reusing TransferReservaFormView for creation
        include __DIR__ . '/../views/TransferReservaFormView.php';
    }

    public function store() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=gestion_reservas');
            exit();
        }
        try {
            $this->model->create($_POST);
            header('Location: ?action=gestion_reservas&status=created');
            exit();
        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

    public function edit($id) {
        if (!Auth::isLoggedIn()) {
            header('Location: ?action=auth');
            exit();
        }
        $reserva = $this->model->getById($id);
        if (!$reserva) {
            header('Location: ?action=gestion_reservas&status=notfound');
            exit();
        }
        $data = $reserva; // Populate form with existing data
        include __DIR__ . '/../views/TransferReservaFormView.php';
    }

    public function update() {
        if (!Auth::isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=gestion_reservas');
            exit();
        }
        try {
            $id = $_POST['id_reserva'] ?? null;
            if (!$id) {
                throw new Exception("ID de reserva no proporcionado.");
            }
            $this->model->update($id, $_POST);
            header('Location: ?action=gestion_reservas&status=updated');
            exit();
        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

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
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }

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
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }
}
