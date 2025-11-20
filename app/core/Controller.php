<?php
// Controller.php - Clase base para todos los controladores de la aplicación.

/**
 * Clase Controller
 * Proporciona funcionalidades comunes que todos los controladores pueden heredar,
 * como la carga de vistas y las redirecciones.
 */
class Controller {
    /**
     * @var PDO|null Instancia de la conexión a la base de datos.
     */
    protected $pdo;

    /**
     * Constructor de la clase Controller.
     * Almacena la conexión a la base de datos para que esté disponible en los controladores hijos.
     *
     * @param PDO $pdo La instancia de la conexión PDO.
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Carga y renderiza una vista.
     * Este método hace que las variables del array $data estén disponibles dentro del archivo de la vista.
     *
     * @param string $viewName El nombre del archivo de la vista (sin la extensión .php).
     * @param array $data Un array asociativo de datos para pasar a la vista.
     */
    protected function view($viewName, $data = []) {
        // Convierte las claves del array $data en variables.
        // Por ejemplo, si $data = ['user' => 'John'], se crea una variable $user con el valor 'John'.
        extract($data);
        
        // Construye la ruta completa al archivo de la vista.
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        // Verifica si el archivo de la vista existe antes de intentar incluirlo.
        if (!file_exists($viewPath)) {
            die("View file not found: {$viewPath}");
        }

        // Utiliza el buffer de salida para capturar el contenido de la vista en una variable.
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        // Imprime el contenido capturado, que es el HTML renderizado de la vista.
        echo $content;
    }

    /**
     * Redirige al usuario a una URL específica.
     *
     * @param string $url La URL a la que se redirigirá al usuario.
     */
    public function redirect($url) {
        header("Location: $url");
        exit();
    }
}

?>
