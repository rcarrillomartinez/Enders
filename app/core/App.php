<?php
namespace app\Core;

class App {
    private $routes = [];

    public function __construct() {
        $this->routes = [
            '/' => ['App\Controllers\HomeController', 'index'],
            '/login' => ['App\Controllers\AuthController', 'index'],
            '/logout' => ['App\Controllers\AuthController', 'logout'],
            '/register' => ['App\Controllers\AuthController', 'signup'],
            '/register-post' => ['App\Controllers\AuthController', 'register'],
            '/admin' => ['App\Controllers\AdminController', 'index'],
            '/admin/calendario' => ['App\Controllers\AdminController', 'calendar'],
            '/reserva/crear' => ['App\Controllers\ReservationController', 'create'],
            '/reserva/guardar' => ['App\Controllers\ReservationController', 'store'],
            '/reserva/ver' => ['App\Controllers\ReservationController', 'show'],
            '/perfil' => ['App\Controllers\PerfilController', 'index'],
            '/perfil-actualizar' => ['App\Controllers\PerfilController', 'update'],
            '/dashboard' => ['App\Controllers\AuthController', 'dashboard'],
        ];
    }

    public function run() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $script = $_SERVER['SCRIPT_NAME'];
        $base = rtrim(dirname($script), '/\\');
        if ($base && $base !== '/') {
            $path = substr($path, strlen($base));
            if ($path === false) $path = '/';
        }
        $path = $path ?: '/';

        if (isset($this->routes[$path])) {
            [$controller, $method] = $this->routes[$path];
            $c = new $controller();
            call_user_func([$c, $method]);
        } else {
            http_response_code(404);
            echo "<h1>404 - Página no encontrada</h1>";
        }
    }
}
