<?php

/**
 * 10 de Diciembre 2025 12:05 AM 
 */

// La clase principal que envía correos

namespace Services;

require_once ROOT_PATH . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Clase MailSender, maneja el envío de correos electrónicos utilizando PHPMailer
 */
class mailSender {

    /** @var PHPMailer */
    private PHPMailer $mailer;

    /**
     * Constructor de la clase MailSender
     * @param string $host Servidor SMTP
     * @param string $username Usuario SMTP     
     * @param string $password Contraseña SMTP
     * @param int $port Puerto SMTP
     * @param bool $useTLS Indica si se debe usar TLS
     * @return void
     */

    public function __construct(string $host, string $username, string $password, int $port,  bool $useTLS = true) 
    {
        // Instancia PHPMailer
        $this->mailer = new PHPMailer(true);

        // Configuración SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = $host;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $username;
        $this->mailer->Password = $password;
        $this->mailer->Port = $port;
        $this->mailer->CharSet = 'UTF-8';

        $this->mailer->AuthType = 'LOGIN'; // ESTA ES LA QUE TE FALTABA

        if ($useTLS) {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->SMTPDebug = 2;

        }
    }


    /**
     * Envía un correo electrónico
     * @param string $fromEmail Correo del remitente
     * @param string $fromName Nombre del remitente
     * @param string $toEmail Correo del destinatario
     * @param string $subject Asunto del correo
     * @param string $body Cuerpo del correo
     * @return bool Devuelve true si el correo se envió correctamente, false en caso contrario
     */
    public function sendEmail(string $fromEmail, string $fromName, string $toEmail, string $subject, string $body): bool{

        try{

            // Configuración del correo
            $this->mailer->setFrom($fromEmail, $fromName);
            $this->mailer->addAddress($toEmail);
            $this->mailer->isHTML(false); // Correo en texto plano
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;


            return $this->mailer->send();
        }catch  (Exception $e){
            // Manejo de errores
            error_log("Error al enviar correo: " . $this->mailer->ErrorInfo);
            return false;

        }
    }

}