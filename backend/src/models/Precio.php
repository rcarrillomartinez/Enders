<?php
require_once 'DBConnection.php';
class Precio {
    private $db;
    public function __construct(DBConnection $db) { $this->db = $db; }

    public function listar() {
        $res = $this->db->query("SELECT * FROM transfer_precios");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>
