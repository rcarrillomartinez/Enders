<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/TransferReserva.php'; 

class TransferReservaController extends Controller {
    private TransferReserva $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new TransferReserva($pdo);
    }

    public function index(): void {
        if (!Auth::isLoggedIn()) $this->redirect('?action=auth');

        $currentUser = Auth::getCurrentUser();
        $month = $_GET['month'] ?? (int)date('m');
        $year = $_GET['year'] ?? (int)date('Y');

        $reservas = $this->model->getAll($currentUser['user_id'] ?? null, $currentUser['user_type'] ?? null, $month, $year);

        $this->view('CalendarView', [
            'reservas' => $reservas,
            'currentMonth' => $month,
            'currentYear' => $year,
            'message' => $_SESSION['message'] ?? null
        ]);
        unset($_SESSION['message']);
    }

    public function create(): void {
        if (!Auth::isLoggedIn()) $this->redirect('?action=auth');

        $currentUser = Auth::getCurrentUser();

        $hoteles = $this->model->getHoteles();
        $viajeros = $this->model->getViajeros();
        $tipos = $this->model->getTiposReserva();

        // Prellenar el email del cliente si es usuario tipo "viajero"
        $data = [];
        if ($currentUser['user_type'] === 'viajero') {
            $data['email_cliente'] = $currentUser['email'] ?? $currentUser['user_name'];
        }

        $this->view('TransferReservaFormView', [
            'data' => $data,
            'errors' => [],
            'hoteles' => $hoteles,
            'viajeros' => $viajeros,
            'tipos' => $tipos
        ]);
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

        $_SESSION['message'] = $result['message'];
        $this->redirect('?action=index');
    }

    public function cancel(int $id): void {
        if (!Auth::isLoggedIn()) $this->redirect('?action=auth');
        $result = $this->model->cancel($id);
        $_SESSION['message'] = $result['message'];
        $this->redirect('?action=index');
    }
}
