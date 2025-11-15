<?php
class Validator {
    public static function esEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    public static function esRequerido($valor) {
        return !empty($valor);
    }
}
?>
