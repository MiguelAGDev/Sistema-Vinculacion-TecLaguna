
<?php
echo "INICIANDO...\n";

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/vendor/autoload.php';

echo "AUTOLOAD CARGADO\n";


// Configuraciones de correo (SMTP)
// Configuraciones de correo (SMTP)
define('SMTP_HOST','smtp.gmail.com');
define('SMTP_USERNAME','alu.23130638@correo.itlalaguna.edu.mx');
define('SMTP_PASSWORD',value: 'rgynpypvoblvuwma');
//define('SMTP_PASSWORD','feye armf rwwf lbxg');
define('SMTP_PORT',587); 
define('SENDER_EMAIL','alu.23130638@correo.itlalaguna.edu.mx');
define('SENDER_NAME','Sistema de Vinculacion ITL');

require_once ROOT_PATH . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Crear la instancia PHPMailer
$mail = new PHPMailer(true);

$mail->SMTPDebug = 2;
// $mail->Debugoutput = function($str, $level) {
//     echo "DEBUG: $str\n";
// };

try {

    // Config SMTP
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->Port = SMTP_PORT;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    // Remitente
    $mail->setFrom(SENDER_EMAIL, SENDER_NAME);

    // Destinatario (tú mismo para probar)
    $mail->addAddress(SENDER_EMAIL);

    // Contenido
    $mail->Subject = 'Prueba SMTP';
    $mail->Body = 'Esto es un correo de prueba enviado desde PHPMailer.';

    // Enviar
    $mail->send();

    echo "Correo enviado correctamente";

} catch (Exception $e) {
    echo "Error al enviar: " . $mail->ErrorInfo;
}
