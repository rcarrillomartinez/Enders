<?php
namespace app\core;

/**
 * Renderiza las vistas y permite pasar variables
 */
class View {
    public static function render($view, $data = []) {
        extract($data);
        require __DIR__ . '/../views/HeaderView.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/FooterView.php';
    }
}
