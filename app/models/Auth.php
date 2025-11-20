<?php

// Clase Auth: Gestiona la autenticación y registro de diferentes tipos de usuarios.
class Auth {
    private $pdo;
    
    /**
     * Constructor de la clase Auth.
     * @param PDO $pdo Instancia de la conexión a la base de datos.
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Registra un nuevo hotel en la base de datos.
     * @param array $data Datos del hotel a registrar.
     * @return array Un array con el estado de éxito y un mensaje.
     */
    public function registerHotel($data) {
        // Extrae los datos del array, con valores por defecto.
        $usuario = $data['usuario'] ?? '';
        $password = $data['password'] ?? '';
        $nombre_hotel = $data['nombre_hotel'] ?? ''; // This comes from the form
        $id_zona = !empty($data['id_zona']) ? $data['id_zona'] : null;

        if (empty($usuario) || empty($password) || empty($nombre_hotel)) {
            return ['success' => false, 'message' => 'Usuario, contraseña y nombre del hotel son requeridos.'];
        }

        // Verifica si el nombre de usuario ya existe.
        $stmt = $this->pdo->prepare('SELECT id_hotel FROM tranfer_hotel WHERE usuario = :usuario');
        $stmt->execute([':usuario' => $usuario]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El nombre de usuario ya existe.'];
        }

        try {
            // Hashea la contraseña antes de almacenarla
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare(
                'INSERT INTO tranfer_hotel (usuario, password, nombre_hotel, id_zona) 
                 VALUES (:usuario, :password, :nombre_hotel, :id_zona)'
            );
            $result = $stmt->execute([
                ':usuario' => $usuario,
                ':password' => $hashedPassword,
                ':nombre_hotel' => $nombre_hotel,
                ':id_zona' => $id_zona
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Hotel registrado exitosamente. Ahora puedes iniciar sesión.'];
            }
        } catch (PDOException $e) {
            // Captura y devuelve cualquier error durante el registro.
            return ['success' => false, 'message' => 'Error al registrar hotel: ' . $e->getMessage()];
        }
    }

    /**
     * Registra un nuevo viajero en la base de datos.
     * @param string $email Email del viajero.
     * @param string $nombre Nombre del viajero.
     * @param string $apellido1 Primer apellido del viajero.
     * @param string $apellido2 Segundo apellido del viajero.
     * @param string $direccion Dirección del viajero.
     * @param string $codigoPostal Código postal del viajero.
     * @param string $ciudad Ciudad del viajero.
     * @param string $pais País del viajero.
     * @param string $password Contraseña del viajero.
     * @return array Un array con el estado de éxito y un mensaje.
     */
    public function registerViajero($email, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $password) {
        if (empty($email) || empty($nombre) || empty($apellido1) || empty($password)) {
            return ['success' => false, 'message' => 'Email, nombre, apellido y contraseña son requeridos'];
        }

        // Verifica si el email del viajero ya existe.
        $stmt = $this->pdo->prepare('SELECT id_viajero FROM transfer_viajeros WHERE email = :email');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }

        try {
            // Hashea la contraseña antes de almacenarla.
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
            
            // Devuelve el resultado del registro.
            if ($result) {
                return ['success' => true, 'message' => 'Viajero registrado exitosamente', 'id_viajero' => $this->pdo->lastInsertId()];
            }
        } catch (Exception $e) {
            // Captura y devuelve cualquier error durante el registro.
            return ['success' => false, 'message' => 'Error al registrar viajero: ' . $e->getMessage()];
        }
    }

    /**
     * Inicia sesión para un usuario de tipo hotel.
     * @param string $usuario Nombre de usuario del hotel.
     * @param string $password Contraseña del hotel.
     * @return array Un array con el estado de éxito y un mensaje.
     */
    public function loginHotel($usuario, $password) {
        if (empty($usuario) || empty($password)) {
            return ['success' => false, 'message' => 'Usuario y contraseña son requeridos'];
        }

        try {
            // Busca el hotel por su nombre de usuario.
            $stmt = $this->pdo->prepare('SELECT * FROM tranfer_hotel WHERE usuario = :usuario');
            $stmt->execute([':usuario' => $usuario]);
            $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica la contraseña y establece las variables de sesión si es correcta.
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
