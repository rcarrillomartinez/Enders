<?php

class Perfil {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    private function getTableInfo(string $userType): array {
        return match (strtolower($userType)) {
            'admin' => ['table' => 'transfer_admin', 'id_col' => 'id_admin', 'identifier' => 'email'],
            'viajero' => ['table' => 'viajero', 'id_col' => 'id_viajero', 'identifier' => 'email'],
            'hotel' => ['table' => 'hotel', 'id_col' => 'id_hotel', 'identifier' => 'usuario'],
            'vehiculo' => ['table' => 'vehiculo', 'id_col' => 'id_vehiculo', 'identifier' => 'email_conductor'],
            default => ['table' => null, 'id_col' => null, 'identifier' => null],
        };
    }

    public function getProfileData(string $userType, int $userId): array|null {
        $info = $this->getTableInfo($userType);
        if (!$info['table']) return null;
        
        $stmt = $this->db->prepare("SELECT * FROM {$info['table']} WHERE {$info['id_col']} = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $data = $stmt->fetch();
        
        if ($data) {
            unset($data['password']); // Nunca exponer el hash de la contraseña
        }
        return $data;
    }

    public function updateProfile(string $userType, int $userId, array $data): array {
        $info = $this->getTableInfo($userType);
        if (!$info['table']) return ['success' => false, 'message' => 'Tipo de usuario no soportado.'];
        
        $updates = [];
        $params = [':id' => $userId];

        // Lógica de actualización de campos no contraseña (email, nombre, etc.)
        if (isset($data['email']) && $data['email'] && ($info['identifier'] === 'email')) {
            $updates[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['nombre']) && $data['nombre'] && property_exists($info, 'nombre')) { // Asumo que nombre existe para viajeros/admin
             $updates[] = "nombre = :nombre";
             $params[':nombre'] = $data['nombre'];
        }

        // Lógica de actualización de contraseña
        if (!empty($data['new_password'])) {
            if ($data['new_password'] !== ($data['confirm_password'] ?? null)) {
                 return ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
            }
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
            $stmt->execute($params);
            
            // Si el email/identificador cambia, actualizar la variable de sesión user_name
            if (isset($data['email'])) {
                $_SESSION['user_name'] = $data['email'];
            }
            
            return ['success' => true, 'message' => 'Perfil actualizado correctamente.'];
        } catch (\PDOException $e) {
            if ($e->getCode() == '23000') {
                return ['success' => false, 'message' => 'El identificador ya está en uso.'];
            }
            error_log("Error de BD al actualizar perfil: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error de base de datos.'];
        }
    }
}

