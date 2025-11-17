<?php
namespace app\models;

use app\core\Database;
use PDO;

class Viajero {
    private $pdo;
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_viajeros WHERE id_viajero=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM transfer_viajeros");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
