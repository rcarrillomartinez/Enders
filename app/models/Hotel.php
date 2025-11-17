<?php
namespace app\models;

use app\core\Database;
use PDO;

class Hotel {
    private $pdo;
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT nombre_hotel FROM tranfer_hotel ORDER BY nombre_hotel ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tranfer_hotel WHERE id_hotel=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($usuario, $id_zona, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO tranfer_hotel (usuario,id_zona,password) VALUES (?,?,?)");
        $stmt->execute([$usuario,$id_zona,$hash]);
        return $this->pdo->lastInsertId();
    }
}
