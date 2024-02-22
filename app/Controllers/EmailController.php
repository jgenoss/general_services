<?php

namespace App\Controllers;

require_once './PHPMailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use App\Controllers\FunctionController;

class EmailController
{
    private $functionController;

    public function __construct()
    {
        $this->functionController = new FunctionController();

    }
    public function sendMail($remitente, $destinatario, $asunto, $contenido)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = '';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            // Configuración del servidor SMTP

            // Configuración del remitente y destinatario
            $mail->setFrom($remitente);
            $mail->addAddress($destinatario);

            // Configuración del contenido del correo electrónico
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $contenido;

            // Enviar el correo electrónico
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
