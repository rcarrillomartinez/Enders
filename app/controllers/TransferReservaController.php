<?php

require_once __DIR__ . '/../models/TransferReserva.php';

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
        try {
            $reservas = $this->model->getAll();
            $total = $this->model->count();
            
            // Pass data to view
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
        try {
            $reserva = $this->model->getById($id);
            
            if (!$reserva) {
                echo '<div style="color: red; padding: 20px;">';
                echo '<h2>Error</h2>';
                echo '<p>Reservation not found.</p>';
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

    /**
     * Show the create form
     */
    public function create() {
        // show form view
        include __DIR__ . '/../views/TransferReservaCreateView.php';
    }

    /**
     * Handle form submission and store the new reservation
     */
    public function store() {
        try {
            // Basic validation / sanitization
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

            // Redirect to show the newly created reservation
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
