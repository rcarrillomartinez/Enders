<?php

require_once __DIR__ . '/../models/TransferReserva.php';
require_once __DIR__ . '/../models/Auth.php';

class TransferReservaController {
    private $model;
    private $pdo;

    public function __construct(PDO $pdo) {
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
        include __DIR__ . '/../views/TransferReservaCreateView.php';
    }

    public function store() {
        try {
            
            $data = [
                'id_viajero' => isset($_POST['id_viajero']) ? intval($_POST['id_viajero']) : null,
                'id_transfer' => isset($_POST['id_transfer']) ? intval($_POST['id_transfer']) : null,
                'fecha_reserva' => $_POST['fecha_reserva'] ?? null,
                'fecha_partida' => $_POST['fecha_partida'] ?? null,
                'hora_partida' => $_POST['hora_partida'] ?? null,
                'num_pasajeros' => isset($_POST['num_pasajeros']) ? intval($_POST['num_pasajeros']) : null,
                'estado' => $_POST['estado'] ?? null,
            ];

            $insertedId = $this->model->create($data);

            
            header('Location: /index.php?action=show&id=' . $insertedId);
            exit;
        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;">';
            echo '<h2>Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }
}
