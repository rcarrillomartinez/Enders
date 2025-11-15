<?php
class EmailService {
    public static function enviar($para, $asunto, $mensaje) {
        $headers = "From: no-reply@islatransfers.com\r\n";
        return mail($para, $asunto, $mensaje, $headers);
    }
}
?>
