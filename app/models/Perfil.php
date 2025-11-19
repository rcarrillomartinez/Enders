<?php

// Clase Perfil: Gestiona las operaciones relacionadas con el perfil de usuario.
class Perfil {
    private PDO $db;

    /**
     * Constructor de la clase Perfil.
     * @param PDO $db Instancia de la conexión a la base de datos.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    /**
     * Obtiene información de la tabla y columnas relevantes según el tipo de usuario.
     * @param string $userType El tipo de usuario (admin, viajero, hotel, vehiculo).
     * @return array Un array asociativo con el nombre de la tabla, la columna ID y el identificador.
     */
    private function getTableInfo(string $userType): array {
        return match (strtolower($userType)) {
            'admin' => ['table' => 'transfer_admin', 'id_col' => 'id_admin', 'identifier' => 'email'],
            'viajero' => ['table' => 'transfer_viajeros', 'id_col' => 'id_viajero', 'identifier' => 'email'],
            'hotel' => ['table' => 'hotel', 'id_col' => 'id_hotel', 'identifier' => 'usuario'],
            'vehiculo' => ['table' => 'vehiculo', 'id_col' => 'id_vehiculo', 'identifier' => 'email_conductor'],
            default => ['table' => null, 'id_col' => null, 'identifier' => null],
        };
    }

    /**
     * Obtiene los datos del perfil de un usuario específico.
     * @param string $userType El tipo de usuario.
     * @param int $userId El ID del usuario.
     * @return array|null Un array asociativo con los datos del perfil o null si no se encuentra.
     */
    public function getProfileData(string $userType, int $userId): array|null {
        $info = $this->getTableInfo($userType);
        if (!$info['table']) return null;
        
        $stmt = $this->db->prepare("SELECT * FROM {$info['table']} WHERE {$info['id_col']} = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        // Obtiene los datos del perfil.
        $data = $stmt->fetch();
        
        if ($data) {
            unset($data['password']); // Nunca exponer el hash de la contraseña
        }
        return $data;
    }

    /**
     * Actualiza los datos del perfil de un usuario.
     * @param string $userType El tipo de usuario.
     * @param int $userId El ID del usuario.
     * @param array $data Un array asociativo con los datos a actualizar.
     * @return array Un array con el estado de éxito y un mensaje.
     */
    public function updateProfile(string $userType, int $userId, array $data): array {
        $info = $this->getTableInfo($userType);
        if (!$info['table']) return ['success' => false, 'message' => 'Tipo de usuario no soportado.'];
        
        $updates = [];
        $params = [':id' => $userId];

        // Lógica para actualizar campos como email y nombre.
        if (isset($data['email']) && $data['email'] && ($info['identifier'] === 'email')) {
            $updates[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['nombre']) && $data['nombre']) { 
             $updates[] = "nombre = :nombre";
             // Añade el campo 'nombre' a la lista de actualizaciones.
             $params[':nombre'] = $data['nombre'];
             // Asigna el valor del nombre a los parámetros.
        }

        // Lógica de actualización de contraseña
        if (!empty($data['new_password'])) {
            if ($data['new_password'] !== ($data['confirm_password'] ?? null)) {
                 return ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
            }
            // Hashea la nueva contraseña antes de almacenarla.
            $hashedPassword = password_hash($data['new_password'], PASSWORD_BCRYPT);
            $updates[] = "password = :password";
            $params[':password'] = $hashedPassword;
        }

        if (empty($updates)) {
            return ['success' => true, 'message' => 'No se realizaron cambios.'];
        }

        $sql = "UPDATE {$info['table']} SET " . implode(', ', $updates) . " WHERE {$info['id_col']} = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            // Ejecuta la consulta de actualización.
            $stmt->execute($params);
            
            // Si el email/identificador cambia, actualiza la variable de sesión user_name.
            if (isset($data['email'])) {
                $_SESSION['user_name'] = $data['email'];
            }
            // Devuelve un mensaje de éxito.
            
            return ['success' => true, 'message' => 'Perfil actualizado correctamente.'];
        } catch (\PDOException $e) {
            if ($e->getCode() == '23000') {
                return ['success' => false, 'message' => 'El identificador ya está en uso.'];
            }
            error_log("Error de BD al actualizar perfil: " . $e->getMessage());
            // Registra el error y devuelve un mensaje de fallo.
            return ['success' => false, 'message' => 'Error de base de datos.'];
        }
    }
}
