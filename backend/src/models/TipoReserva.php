<?php
require_once 'DBConnection.php';
class TipoReserva {
    private $db;
    public function __construct(DBConnection $db) { $this->db = $db; }

    public function listar() {
        $res = $this->db->query("SELECT * FROM transfer_tipo_reserva");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>
