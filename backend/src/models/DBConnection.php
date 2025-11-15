<?php
class DBConnection {
    private $conn;

    private function loadEnv($path) {
        $realPath = realpath($path);
        if (!$realPath || !file_exists($realPath)) {
            throw new Exception("El archivo .env no existe en: $path");
        }
        $lines = file($realPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Ignora comentarios
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }


    public function __construct() {
        // Cargar variables de entorno al inicio
        $this->loadEnv(__DIR__ . '/../../.env');

        // Crear conexión usando las variables cargadas
        $this->conn = new mysqli(
            $_ENV['DB_HOST'], 
            $_ENV['DB_USER'], 
            $_ENV['DB_PASS'], 
            $_ENV['DB_NAME']
        );

        if ($this->conn->connect_error) {
            die("Error conexión DB: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }
    public function escape($str) {
        return $this->conn->real_escape_string($str);
    }
    public function getConnection() {
        return $this->conn;
    }
}
?>
