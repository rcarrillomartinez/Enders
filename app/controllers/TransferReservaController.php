<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/TransferReserva.php'; 

class TransferReservaController extends Controller {
    private TransferReserva $model;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
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

    public function edit(int $id): void {
        if (!Auth::isLoggedIn()) $this->redirect('?action=auth');

        $reserva = $this->model->find($id); // ✅ usa la propiedad inicializada
        if (!$reserva) {
            $_SESSION['message'] = 'Error: Reserva no encontrada.';
            $this->redirect('?action=index');
        }

        $currentUser = Auth::getCurrentUser();
        if ($currentUser['user_type'] !== 'admin' && $currentUser['user_id'] !== $reserva['id_viajero']) {
            $_SESSION['message'] = 'Error: No tienes permiso para editar esta reserva.';
            $this->redirect('?action=index');
        }

        $this->view('TransferReservaView', ['reserva' => $reserva, 'data' => $reserva, 'errors' => []]);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Auth::isLoggedIn()) $this->redirect('?action=index');

        $data = $_POST;
        $currentUser = Auth::getCurrentUser();

        if (isset($data['id_reserva'])) {
            $result = $this->model->update($data);
        } else {
            $idViajero = ($currentUser['user_type'] === 'viajero') ? $currentUser['user_id'] : null;
            $result = $this->model->create($data, $idViajero);
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
