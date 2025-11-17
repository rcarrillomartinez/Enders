<?php

class Controller {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    protected function view($viewName, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (!file_exists($viewPath)) {
            die("View file not found: {$viewPath}");
        }

        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        echo $content;
    }

    public function redirect($url) {
        header("Location: $url");
        exit();
    }
}

?>
