<?php
namespace app\core;

/**
 * Clase base para todos los controladores
 */
class Controller {
    protected $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function view($view, $data = []) {
        View::render($view, $data);
    }

    public function redirect($url) {
        header("Location: $url");
        exit();
    }
}
