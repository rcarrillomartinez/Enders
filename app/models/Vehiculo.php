<?php

class Vehiculo {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Obtener todos los vehículos
    public function all() {
        $stmt = $this->pdo->query("
            SELECT id_vehiculo AS id, descripcion AS nombre
            FROM transfer_vehiculo
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar un vehículo por ID
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_vehiculo WHERE id_vehiculo=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo vehículo
    public function create($descripcion, $capacidad, $email_conductor, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("
            INSERT INTO transfer_vehiculo (descripcion, capacidad, email_conductor, password) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$descripcion, $capacidad, $email_conductor, $hash]);
        return $this->pdo->lastInsertId();
    }

    public function getByCapacity($num) {
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_vehiculo WHERE capacidad >= :capacidad ORDER BY capacidad ASC");
        $stmt->execute(['capacidad' => $num]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
