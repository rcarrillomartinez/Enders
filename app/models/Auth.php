<?php

class Auth {
    private PDO $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
  
    // Métodos de Mapeo / Utilidad (Mapper)

    private function getTableName(string $userType): string|null {
        return match (strtolower($userType)) {
            'viajero' => 'transfer_viajeros', 
            'vehiculo' => 'transfer_vehiculo',
            'hotel' => 'tranfer_hotel', 
            'admin' => 'transfer_admin',
            default => null,
        };
    }
    
    private function getIdColumn(string $userType): string|null {
        return match (strtolower($userType)) {
            'viajero' => 'id_viajero', 
            'vehiculo' => 'id_vehiculo',
            'hotel' => 'id_hotel', 
            'admin' => 'id_admin',
            default => null,
        };
    }
    
    private function getIdentifierColumn(string $userType): string|null {
        return match (strtolower($userType)) {
            'viajero', 'admin' => 'email',
            'vehiculo' => 'email_conductor',
            'hotel' => 'usuario',
            default => null,
        };
    }

    // Métodos de sesión (Estáticos)
    
    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
    }

    public static function isAdmin(): bool {
        return self::isLoggedIn() && $_SESSION['user_type'] === 'admin';
    }

    public static function getCurrentUser(): array|null {
        if (self::isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'user_type' => $_SESSION['user_type'],
                'user_name' => $_SESSION['user_name'] ?? 'Usuario',
                'email' => $_SESSION['email'] ?? '', 
            ];
        }
        return null;
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
        $_SESSION = [];
    }

    // Lógica de Login (Centralizada) 

    /**
     * Intenta loggear al usuario basándose en el tipo y credenciales.
     */
    public function login(string $userType, string $identifier, string $password): array {
        if (empty($identifier) || empty($password)) {
            return ['success' => false, 'message' => 'Identificador y contraseña son requeridos'];
        }

        $userType = strtolower($userType);
        $tableName = $this->getTableName($userType);
        $idCol = $this->getIdColumn($userType);
        $identifierCol = $this->getIdentifierColumn($userType);

        if (!$tableName) {
            return ['success' => false, 'message' => "Tipo de usuario inválido."];
        }

        try {
            // Seleccionamos todos los campos necesarios
            $stmt = $this->db->prepare("SELECT * FROM {$tableName} WHERE {$identifierCol} = :identifier");
            $stmt->execute([':identifier' => $identifier]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                // Determinar nombre y email para la sesión
                $userName = $identifier; // por defecto
                $userEmail = $identifier; // por defecto

                switch ($userType) {
    case 'viajero':
    case 'admin':
        $_SESSION['email'] = $user['email'];
        break;

    case 'vehiculo':
        $_SESSION['email'] = $user['email_conductor'];
        break;

    case 'hotel':
        $_SESSION['email'] = $user['usuario'];  // O cámbialo si tienes un campo email real
        break;

    default:
        $_SESSION['email'] = '';
}


                // Guardamos en sesión
                $_SESSION['user_id'] = $user[$idCol];
                $_SESSION['user_type'] = $userType;
                $_SESSION['user_name'] = $userName;
                $_SESSION['email'] = $user[$this->getIdentifierColumn($userType)]; 

                return ['success' => true, 'message' => "¡Bienvenido!"];
            } else {
                return ['success' => false, 'message' => 'Credenciales incorrectas.'];
            }
        } catch (\PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error de base de datos durante el login.'];
        }
    }


    // lógica de registro (Individual) 

    /**
     * Registra un hotel.
     */
    public function registerHotel(array $data): array {
        if (empty($data['usuario']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Usuario y contraseña son requeridos'];
        }

        try {
            $stmt = $this->db->prepare('SELECT id_hotel FROM tranfer_hotel WHERE usuario = :usuario');
            $stmt->execute([':usuario' => $data['usuario']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'El usuario ya existe'];
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare('INSERT INTO tranfer_hotel (usuario, password, id_zona) VALUES (:usuario, :password, :id_zona)');
            $result = $stmt->execute([
                ':usuario' => $data['usuario'],
                ':password' => $hashedPassword,
                ':id_zona' => $data['id_zona'] ?? null
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Hotel registrado exitosamente', 'id_hotel' => $this->db->lastInsertId()];
            }
        } catch (\PDOException $e) {
            error_log("Registro Hotel error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error de base de datos al registrar.'];
        }
        return ['success' => false, 'message' => 'Error al registrar hotel.'];
    }

    /**
     * Registra un vehículo/conductor.
     */
    public function registerVehiculo(array $data): array {
        if (empty($data['email_conductor']) || empty($data['descripcion']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Email, descripción y contraseña son requeridos'];
        }

        try {
            $stmt = $this->db->prepare('SELECT id_vehiculo FROM transfer_vehiculo WHERE email_conductor = :email');
            $stmt->execute([':email' => $data['email_conductor']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'El email del conductor ya está registrado'];
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare('INSERT INTO transfer_vehiculo (email_conductor, Descripción, password) VALUES (:email, :descripcion, :password)');
            $result = $stmt->execute([
                ':email' => $data['email_conductor'],
                ':descripcion' => $data['descripcion'],
                ':password' => $hashedPassword
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Vehículo registrado exitosamente', 'id_vehiculo' => $this->db->lastInsertId()];
            }
        } catch (\PDOException $e) {
            error_log("Registro Vehiculo error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error de base de datos al registrar.'];
        }
        return ['success' => false, 'message' => 'Error al registrar vehículo.'];
    }

    /**
     * Registra un viajero.
     */
    public function registerViajero(array $data): array {
        if (empty($data['email']) || empty($data['nombre']) || empty($data['apellido1']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Email, nombre, apellido y contraseña son requeridos'];
        }

        try {
            $stmt = $this->db->prepare('SELECT id_viajero FROM transfer_viajeros WHERE email = :email');
            $stmt->execute([':email' => $data['email']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'El email ya está registrado'];
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare('
                INSERT INTO transfer_viajeros 
                (email, nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, password) 
                VALUES (:email, :nombre, :apellido1, :apellido2, :direccion, :codigoPostal, :ciudad, :pais, :password)
            ');
            $result = $stmt->execute([
                ':email' => $data['email'],
                ':nombre' => $data['nombre'],
                ':apellido1' => $data['apellido1'],
                ':apellido2' => $data['apellido2'] ?? '',
                ':direccion' => $data['direccion'] ?? '',
                ':codigoPostal' => $data['codigoPostal'] ?? '',
                ':ciudad' => $data['ciudad'] ?? '',
                ':pais' => $data['pais'] ?? '',
                ':password' => $hashedPassword
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Viajero registrado exitosamente', 'id_viajero' => $this->db->lastInsertId()];
            }
        } catch (\PDOException $e) {
             error_log("Registro Viajero error: " . $e->getMessage());
             return ['success' => false, 'message' => 'Error de base de datos al registrar.'];
        }
        return ['success' => false, 'message' => 'Error al registrar viajero.'];
    }
    
    /**
     * Registra un usuario administrador.
     */
    public function registerAdmin(array $data): array {
        if (empty($data['email']) || empty($data['nombre']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Email, nombre y contraseña son requeridos'];
        }

        try {
            $stmt = $this->db->prepare('SELECT id_admin FROM transfer_admin WHERE email = :email');
            $stmt->execute([':email' => $data['email']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'El email del admin ya está registrado'];
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare('INSERT INTO transfer_admin (email, nombre, password) VALUES (:email, :nombre, :password)');
            $result = $stmt->execute([
                ':email' => $data['email'],
                ':nombre' => $data['nombre'],
                ':password' => $hashedPassword
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Admin registrado exitosamente', 'id_admin' => $this->db->lastInsertId()];
            }
        } catch (\PDOException $e) {
            error_log("Registro Admin error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error de base de datos al registrar.'];
        }
        return ['success' => false, 'message' => 'Error al registrar admin.'];
    }
}