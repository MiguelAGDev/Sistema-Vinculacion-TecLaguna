<?php

/** 09 de Diciembre del 2025 8:21 PM 
 * Servicio para el envío de correos electrónicos
 */

/**
 * Summary of MailServices
 * Clase MailServices, maneja el envío de correos electrónicos
 */
class MailServices
{
public function enviarVerificacion($correoDestino, $codigoVerificacion): bool{
        
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            // Configuración del correo
            $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
            $mail->addAddress($correoDestino);
            $mail->Subject = 'Activación de cuenta - Sistema de Vinculacion';

            // Construcción del enlace de verificación
            $urlBase = "https://localhost/index.php";
            $enlaceVerificacion = "{$urlBase}?url=verificacion&token={$codigoVerificacion} ";

            // Cuerpo del correo
            $cuerpo = "
             Hola, 
             Gracias por registrarte en el Sistema de Vinculacion.
             Para activar tu cuenta has clicl en el siguiente enlace
             
                {$enlaceVerificacion}

            El enlace expira en X tiempo    
            Atentamente, soporte Sistema Vinculacion
             ";

            $mail->Body = $cuerpo;

            // Enviar el correo
            $mail->send();
            return true;

        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }

    }
    

}