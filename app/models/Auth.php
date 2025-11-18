<?php

class Auth {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Register a hotel
     */
    public function registerHotel($nombre, $usuario, $password, $id_zona = null) {
        if (empty($nombre) || empty($usuario) || empty($password)) {
            return ['success' => false, 'message' => 'Nombre, usuario y contraseña son requeridos'];
        }

        // Check if usuario already exists
        $stmt = $this->pdo->prepare('SELECT id_hotel FROM tranfer_hotel WHERE usuario = :usuario');
        $stmt->execute([':usuario' => $usuario]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El usuario ya existe'];
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare('INSERT INTO tranfer_hotel (nombre, usuario, password, id_zona) VALUES (:nombre, :usuario, :password, :id_zona)');
            $result = $stmt->execute([
                ':usuario' => $usuario,
                ':nombre' => $nombre,
                ':password' => $hashedPassword,
                ':id_zona' => $id_zona
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Hotel registrado exitosamente', 'id_hotel' => $this->pdo->lastInsertId()];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al registrar hotel: ' . $e->getMessage()];
        }
    }

    /**
     * Register a vehicle/conductor
     */
    public function registerVehiculo($email_conductor, $descripcion, $password) {
        if (empty($email_conductor) || empty($descripcion) || empty($password)) {
            return ['success' => false, 'message' => 'Email, descripción y contraseña son requeridos'];
        }

        // Check if email already exists
        $stmt = $this->pdo->prepare('SELECT id_vehiculo FROM transfer_vehiculo WHERE email_conductor = :email');
        $stmt->execute([':email' => $email_conductor]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El email del conductor ya está registrado'];
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare('INSERT INTO transfer_vehiculo (email_conductor, Descripción, password) VALUES (:email, :descripcion, :password)');
            $result = $stmt->execute([
                ':email' => $email_conductor,
                ':descripcion' => $descripcion,
                ':password' => $hashedPassword
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Vehículo registrado exitosamente', 'id_vehiculo' => $this->pdo->lastInsertId()];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al registrar vehículo: ' . $e->getMessage()];
        }
    }

    /**
     * Register a traveler
     */
    public function registerViajero($email, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $password) {
        if (empty($email) || empty($nombre) || empty($apellido1) || empty($password)) {
            return ['success' => false, 'message' => 'Email, nombre, apellido y contraseña son requeridos'];
        }

        // Check if email already exists
        $stmt = $this->pdo->prepare('SELECT id_viajero FROM transfer_viajeros WHERE email = :email');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare('
                INSERT INTO transfer_viajeros 
                (email, nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, password) 
                VALUES (:email, :nombre, :apellido1, :apellido2, :direccion, :codigoPostal, :ciudad, :pais, :password)
            ');
            $result = $stmt->execute([
                ':email' => $email,
                ':nombre' => $nombre,
                ':apellido1' => $apellido1,
                ':apellido2' => $apellido2 ?? '',
                ':direccion' => $direccion ?? '',
                ':codigoPostal' => $codigoPostal ?? '',
                ':ciudad' => $ciudad ?? '',
                ':pais' => $pais ?? '',
                ':password' => $hashedPassword
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Viajero registrado exitosamente', 'id_viajero' => $this->pdo->lastInsertId()];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al registrar viajero: ' . $e->getMessage()];
        }
    }

    /**
     * Login hotel
     */
    public function loginHotel($usuario, $password) {
        if (empty($usuario) || empty($password)) {
            return ['success' => false, 'message' => 'Usuario y contraseña son requeridos'];
        }

        try {
            $stmt = $this->pdo->prepare('SELECT * FROM tranfer_hotel WHERE usuario = :usuario');
            $stmt->execute([':usuario' => $usuario]);
            $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($hotel && password_verify($password, $hotel['password'])) {
                $_SESSION['user_type'] = 'hotel';
                $_SESSION['user_id'] = $hotel['id_hotel'];
                $_SESSION['user_name'] = $hotel['usuario'];
                $_SESSION['user_email'] = null;
                return ['success' => true, 'message' => 'Inicio de sesión exitoso', 'user' => $hotel];
            }

            return ['success' => false, 'message' => 'Usuario o contraseña incorrectos'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error en el login: ' . $e->getMessage()];
        }
    }

    /**
     * Login vehicle/conductor
     */
    public function loginVehiculo($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email y contraseña son requeridos'];
        }

        try {
            $stmt = $this->pdo->prepare('SELECT * FROM transfer_vehiculo WHERE email_conductor = :email');
            $stmt->execute([':email' => $email]);
            $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($vehiculo && password_verify($password, $vehiculo['password'])) {
                $_SESSION['user_type'] = 'vehiculo';
                $_SESSION['user_id'] = $vehiculo['id_vehiculo'];
                $_SESSION['user_name'] = $vehiculo['email_conductor'];
                $_SESSION['user_email'] = $vehiculo['email_conductor'];
                return ['success' => true, 'message' => 'Inicio de sesión exitoso', 'user' => $vehiculo];
            }

            return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error en el login: ' . $e->getMessage()];
        }
    }

    /**
     * Login traveler
     */
    public function loginViajero($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email y contraseña son requeridos'];
        }

        try {
            $stmt = $this->pdo->prepare('SELECT * FROM transfer_viajeros WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $viajero = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($viajero && password_verify($password, $viajero['password'])) {
                $_SESSION['user_type'] = 'viajero';
                $_SESSION['user_id'] = $viajero['id_viajero'];
                $_SESSION['user_name'] = $viajero['nombre'] . ' ' . $viajero['apellido1'];
                $_SESSION['user_email'] = $viajero['email'];
                return ['success' => true, 'message' => 'Inicio de sesión exitoso', 'user' => $viajero];
            }

            return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error en el login: ' . $e->getMessage()];
        }
    }

    /**
     * Register an admin user
     */
    public function registerAdmin($email, $nombre, $password) {
        if (empty($email) || empty($nombre) || empty($password)) {
            return ['success' => false, 'message' => 'Email, nombre y contraseña son requeridos'];
        }

        // Check if email already exists in admin table
        $stmt = $this->pdo->prepare('SELECT id_admin FROM transfer_admin WHERE email = :email');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El email del admin ya está registrado'];
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare('INSERT INTO transfer_admin (email, nombre, password) VALUES (:email, :nombre, :password)');
            $result = $stmt->execute([
                ':email' => $email,
                ':nombre' => $nombre,
                ':password' => $hashedPassword
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Admin registrado exitosamente', 'id_admin' => $this->pdo->lastInsertId()];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al registrar admin: ' . $e->getMessage()];
        }
    }

    /**
     * Login admin
     */
    public function loginAdmin($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email y contraseña son requeridos'];
        }

        try {
            $stmt = $this->pdo->prepare('SELECT * FROM transfer_admin WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['user_type'] = 'admin';
                $_SESSION['user_id'] = $admin['id_admin'];
                $_SESSION['user_name'] = $admin['nombre'];
                $_SESSION['user_email'] = $admin['email'];
                return ['success' => true, 'message' => 'Inicio de sesión exitoso', 'user' => $admin];
            }

            return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error en el login: ' . $e->getMessage()];
        }
    }

    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Sesión cerrada'];
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
    }

    /**
     * Get current user info
     */
    public static function getCurrentUser() {
        if (self::isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'user_type' => $_SESSION['user_type'],
                'user_name' => $_SESSION['user_name'] ?? 'Usuario',
                'user_email' => $_SESSION['user_email'] ?? null
            ];
        }
        return null;
    }
}
?>
