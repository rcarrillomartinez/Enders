<?php

class Controller {
    protected PDO $db;

    /**
     * Constructor opcional para recibir una conexión PDO.
     * Si no se pasa, se puede obtener usando Database::getInstance()->getConnection().
     */
    public function __construct(?PDO $db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = Database::getInstance()->getConnection();
        }

        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Carga un modelo desde /app/models/ y lo instancia con PDO.
     */
    protected function model(string $model): object {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';
        if (!file_exists($modelFile)) {
            throw new Exception("Modelo no encontrado: $modelFile");
        }
        require_once $modelFile;
        return new $model($this->db);
    }

    /**
     * Carga una vista desde /app/views/ y le pasa los datos.
     */
    protected function view(string $view, array $data = []): void {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("Vista no encontrada: $viewFile");
        }
        require $viewFile;
    }

    /**
     * Redirige a otra URL.
     */
    protected function redirect(string $url): void {
        header("Location: $url");
        exit;
    }
}
?>
