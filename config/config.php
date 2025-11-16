<?php

/*11 de Noviembre 2025, 11:35 PM*/
/// config/Config.php
// Configuracion global del sistema - Etapa Local

// Ruta absoluta o relativa para el archivo de log de errores PHP
ini_set('error_log', __DIR__ . '/../logs/access.log');

//Entorno y errores 
date_default_timezone_set('America/Mexico_City');
error_reporting(E_ALL);
ini_set('display_errors',1); // El 1 solo en desrrollo y cambia a 0 den produccion


// Rutas absolutas en sistemas de archivos 
// para includes/require
// verificar la ruta de cada maquina (azu y jaunpi)

define('ROOT_PATH',realpath(dirname(__DIR__))); 
// La ruta de arriba es equivalente a C:\xampp\htdocs\Sistema-Vinculacion-TecLaguna
//\\
define('CONFIG_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'config');
define('DATABASE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'database');
define('SRC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'src');
define('ASSETS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'assets');
define('UPLOADS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');


//URL's publicas (para enlaces en HTML)
define('BASE_URL','http://localhost/Sistema-Vinculacion-TecLaguna/'); // ajusta si tu vhost es distinto);
// Si más adelante usas un virtual host (por ejemplo http://vinculacion.local/), actualizas BASE_URL a eso.
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOAD_URL',BASE_URL . 'uploads/');



// Informacion de la aplicaciones
define('APP_NAME', 'Sistema de Vinculacion del Tecnologico de la Laguna');
define('APP_ENV','development');

// Oracle Wallet - conexion
// DICHAS CONEXXIONES SON NECESARIAS PARA EL CONSTRUCTOR DE LA CLASE CONEXION
define('ORACLE_TNS_ADMIN', 'C:\\oracle\\wallet');

define('ORACLE_INSTANT_CLIENT','C:\\oracle\\wallet\\instantclient_19_20');
define('ORACLE_LD_LIBRARY_PATH', 'LD_LIBRARY_PATH='.ORACLE_INSTANT_CLIENT);
define('ORACLE_SSL_CERT_FILE', "SSL_CERT_FILE=" . ORACLE_TNS_ADMIN . "\\ewallet.p12");

// CREDENCIALES BASE DE DATOS
define('ORACLE_ADMIN','ADMIN');
define('ORACLE_PASSWORD','Abc123456789___');
define('ORACLE_SERVICE_NAME', 'vinculacioninstitutotecdelalag_high');



// La sesion arranca solo si hay una sesion activa.
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

//Importante: session_start() debe ejecutarse antes de enviar cualquier 
// salida (headers). Si incluyes Config.php después de imprimir texto 
// verás “headers already sent”. La regla práctica: incluye Config.php 
// al comienzo del entrypoint (antes de cualquier echo/HTML).