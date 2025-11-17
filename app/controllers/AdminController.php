<?php
namespace app\controllers;

use app\core\Controller;
use app\models\TransferReserva;
use app\models\Auth;

class AdminController extends Controller {
    private $reservaModel;
    private $hotelModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->reservaModel = new TransferReserva($pdo);
        $this->hotelModel = new Hotel($pdo);
    }

    private function ensureAdmin() {
        if (!Auth::isLoggedIn() || Auth::getCurrentUser()['user_type'] !== 'admin') {
            die('Acceso denegado');
        }
    }

    public function index() {
        $this->ensureAdmin();
        $reservas = $this->reservaModel->getAll();
        $this->view('AdminDashboardView', ['reservas' => $reservas]);
    }

    public function createReserva() {
        $this->ensureAdmin();
        $hoteles = $this->hotelModel->getAll();
        $this->view('AdminReservaCreateView', ['hoteles' => $hoteles]);
    }

    public function storeReserva() {
        $this->ensureAdmin();
        $data = $_POST;
        $id = $this->reservaModel->create($data);
        if ($id) {
            header('Location: ?action=adminDashboard');
            exit();
        } else {
            $this->view('AdminReservaCreateView', ['error' => 'No se pudo crear reserva']);
        }
    }

    public function calendar() {
        $this->ensureAdmin();
        $view = $_GET['view'] ?? 'week';
        $date = $_GET['date'] ?? date('Y-m-d');
        $reservas = $this->reservaModel->byRange($view, $date);
        $this->view('AdminCalendarView', [
            'reservas' => $reservas,
            'view' => $view,
            'date' => $date
        ]);
    }
}
