<?php
namespace app\models;

use app\core\Database;
use PDO;

class Perfil {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getProfileData($user_type, $user_id) {
        $table = $this->getTable($user_type);
        $id_column = $this->getIdColumn($user_type);
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE $id_column=?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($user_type, $user_id, $data) {
        $table = $this->getTable($user_type);
        $id_column = $this->getIdColumn($user_type);

        $fields = [];
        $params = [];
        foreach ($data as $key=>$value) {
            $fields[] = "$key=?";
            $params[] = $value;
        }
        $params[] = $user_id;

        $sql = "UPDATE $table SET ".implode(',', $fields)." WHERE $id_column=?";
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        return ['success'=>$res, 'message'=>$res?'Perfil actualizado':'Error al actualizar perfil'];
    }

    private function getTable($user_type) {
        return match($user_type) {
            'admin' => 'transfer_admin',
            'hotel' => 'tranfer_hotel',
            'vehiculo' => 'transfer_vehiculo',
            default => 'transfer_viajeros'
        };
    }

    private function getIdColumn($user_type) {
        return match($user_type) {
            'admin' => 'id_admin',
            'hotel' => 'id_hotel',
            'vehiculo' => 'id_vehiculo',
            default => 'id_viajero'
        };
    }
}
