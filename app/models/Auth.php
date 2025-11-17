<?php
namespace app\models;

use app\core\Database;
use PDO;

class Auth {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // --- LOGIN ADMIN ---
    public function loginAdmin(string $email, string $password): array {
        $email = trim(strtolower($email));
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_admin WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_admin'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['user_email'] = $user['email'];
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Credenciales inválidas'];
    }

    // --- LOGIN VIAJERO ---
    public function loginViajero(string $email, string $password): array {
        $email = trim(strtolower($email));
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_viajeros WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_viajero'];
            $_SESSION['user_type'] = 'viajero';
            $_SESSION['user_email'] = $user['email'];
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Credenciales inválidas'];
    }

    // --- REGISTRO VIAJERO ---
    public function registerViajero(array $data): array {
        $email = trim(strtolower($data['email']));

        // Comprobar si ya existe
        $stmt = $this->pdo->prepare("SELECT * FROM transfer_viajeros WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }

        $pwHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("
            INSERT INTO transfer_viajeros 
            (nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, email, password) 
            VALUES 
            (:nombre, :apellido1, :apellido2, :direccion, :codigoPostal, :ciudad, :pais, :email, :password)
        ");
        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':apellido1' => $data['apellido1'],
            ':apellido2' => $data['apellido2'] ?? '',
            ':direccion' => $data['direccion'] ?? '',
            ':codigoPostal' => $data['codigoPostal'] ?? '',
            ':ciudad' => $data['ciudad'] ?? '',
            ':pais' => $data['pais'] ?? '',
            ':email' => $email,
            ':password' => $pwHash
        ]);

        return ['success' => true, 'message' => 'Usuario registrado correctamente'];
    }

    // --- LOGOUT ---
    public static function logout(): void {
        session_unset();
        session_destroy();
    }

    // --- UTILS ---
    public static function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    public static function getCurrentUser(): array {
        return [
            'user_id' => $_SESSION['user_id'] ?? null,
            'user_type' => $_SESSION['user_type'] ?? null,
            'user_email' => $_SESSION['user_email'] ?? null
        ];
    }
}
