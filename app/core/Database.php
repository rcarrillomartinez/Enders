<?php
// Database.php - Gestiona la conexión a la base de datos utilizando el patrón Singleton.

/**
 * Clase Database (Singleton)
 * Asegura que solo exista una única instancia de la conexión a la base de datos (PDO)
 * durante toda la ejecución de la aplicación. Esto mejora el rendimiento y la consistencia.
 */
class Database {
    /**
     * @var Database|null La única instancia de la clase Database.
     */
    private static $instance = null;
    /**
     * @var PDO La conexión PDO a la base de datos.
     */
    private $pdo;

    // Propiedades para las credenciales de la base de datos.
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;

    /**
     * Constructor privado para prevenir la creación de nuevas instancias directamente.
     * El constructor es el corazón del Singleton. Al ser privado, la única forma de
     * obtener un objeto de esta clase es a través del método estático getInstance().
     */
    private function __construct()
    {
        // Lee las credenciales de la base de datos desde las variables de entorno (para Docker)
        // o utiliza valores por defecto si no están definidas.
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'enders_db';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->charset = 'utf8mb4';
        
        // Data Source Name (DSN): la cadena de conexión para PDO.
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

        // Opciones de configuración para la conexión PDO.
        $options = [
            // Lanza excepciones en caso de error, lo que permite un manejo de errores más robusto.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Devuelve los resultados como arrays asociativos por defecto.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Desactiva la emulación de sentencias preparadas para mayor seguridad (previene inyección SQL).
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            // Intenta crear la instancia de PDO.
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Si la conexión falla, lanza la excepción para que sea manejada por el bloque try-catch principal.
            throw $e;
        }
    }

    /**
     * Obtiene la instancia única de la clase Database.
     * Si no existe una instancia, la crea. Si ya existe, la devuelve.
     *
     * @return Database La instancia única de la clase.
     */
    public static function getInstance(): Database
    {
        // Comprueba si la instancia aún no ha sido creada.
        if (self::$instance === null) {
            // Si no existe, crea una nueva instancia de esta misma clase.
            self::$instance = new self();
        }
        // Devuelve la instancia (ya sea la recién creada o la existente).
        return self::$instance;
    }

    /**
     * Devuelve el objeto de conexión PDO.
     *
     * @return PDO La conexión PDO activa.
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // Previene la clonación de la instancia (parte del patrón Singleton).
    private function __clone() {}
    // Previene la deserialización de la instancia (parte del patrón Singleton).
    public function __wakeup() {}
}
