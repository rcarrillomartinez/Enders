<?php
class Logger {
    public static function log($msg) {
        file_put_contents(__DIR__ . '/app.log', date('c')." - ".$msg.PHP_EOL, FILE_APPEND);
    }
}
?>
