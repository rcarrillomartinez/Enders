<?php
namespace app\models;

use app\core\Database;

class User {
    private $pdo;
    public function __construct() { $this->pdo = Database::get(); }

    public function find(int $id) {
        $stmt = $this->pdo->prepare('SELECT * FROM transfer_viajeros WHERE id_viajero = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email) {
        $stmt = $this->pdo->prepare('SELECT * FROM transfer_viajeros WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(array $data) {
        $pw = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO transfer_viajeros (nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, email, password) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $data['nombre'] ?? '',
            $data['apellido1'] ?? '',
            $data['apellido2'] ?? '',
            $data['direccion'] ?? '',
            $data['codigoPostal'] ?? '',
            $data['ciudad'] ?? '',
            $data['pais'] ?? '',
            $data['email'],
            $pw
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data) {
        $stmt = $this->pdo->prepare('UPDATE transfer_viajeros SET nombre=?, apellido1=?, email=? WHERE id_viajero=?');
        return $stmt->execute([$data['nombre'] ?? '', $data['apellido1'] ?? '', $data['email'] ?? '', $id]);
    }

    public function verify(string $email, string $password) {
        $u = $this->findByEmail($email);
        if ($u && password_verify($password, $u['password'])) return $u;
        return false;
    }
}
