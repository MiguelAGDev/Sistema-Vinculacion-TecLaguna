<?php

require_once __DIR__ . '/../config/config.php';

// 7 de Noviembre 2025, 3:40 PM
class Conexion{


    
    private $conn = null;

    // Constructor de la clase conexion php
    public function __construct(){
        // Establece la variable de entorno para el oracle wallet
        putenv("TNS_ADMIN=".ORACLE_TNS_ADMIN);
        putenv("PATH=" . getenv("PATH") . PATH_SEPARATOR . "C:\\Conexiones\\Oracle\\instantclient-basic-windows.x64-19.28.0.0.0dbru\\instantclient_19_28");
        putenv("LD_LIBRARY_PATH=C:\\Conexiones\\Oracle\\instantclient-basic-windows.x64-19.28.0.0.0dbru\\instantclient_19_28");
        putenv("SSL_CERT_FILE=" . ORACLE_TNS_ADMIN . "\\ewallet.p12");

    }

    // Metodo conexion, realiza la conexion con la base de datos
    public function conectar(){
        
        // Si la conexion es nula
        if($this->conn == null){
            
            // Se inicializa la conexion
            $this->conn = oci_connect(
                ORACLE_USER, // Usuario de la base de datos
                ORACLE_PASSWORD,// Contrasenha del usuario
                ORACLE_SERVICE_NAME// Nombre del servicio de la base de datos
            );

            // Si la conexion no se realizo
            if(!$this->conn){
                
                $e = oci_error();
                throw new Exception("Error de la conexion: ".$e['message']);

            }



        }// Termina el intento de coenxion

        // retorna la conexion
        return $this->conn;

    }// Final dle metodo conecxion

    
    // Metodo de desconectar
    public function desconectar(){

        // Si existe una conexion
        if($this->conn != null){

            // Cierra la conexion
            oci_close($this->conn);

            //Setea como nulla la conexion
            $this->conn = null;
        }
    }

    public function __destruct(){

        $this->desconectar();
    }
}


// Processar el html
// vamos a tener un metodo 'accion', que va contar con conectar' y 'desconectar'.
//  Los cuales se consultara para realizar el proceso de abrir y cerrar conexion 

//Esta variable solo mostrar el mensje de 'conexion - realizada o no realizada'
$mensaje = '';

// Creamos un nuevo objeto conexion
$conexion = new Conexion();

//Aqui es todo el proceso
// Verificamos si el formulario fue enviado con metodo POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Aqui es donde verificamos el arreglo accion
    // verifica si la variable post en la posicion conectar
    // no es nula
    // el isset es una uncion que verifica si una variable esta definida y no es null

    if(isset($_POST['accion'])){

        // Intentaremos, con el accion recibida en el formulario
        // conectar o desconectar la base de datos
        try{

            // Analizamos lo que se intena ejecutar dentro de accion
            switch($_POST['accion']){

                // En el caso de conectar, realizaremos la conexion y enviaremos
                // el mensje de conexion establecida
                case 'conectar':
                    $conexion->conectar();
                    $mensaje = 'Conexion establecida';
                break;

                // Si se desconceta solo se llama al metodo y se envia un emnsaje
                case 'desconectar':
                    $conexion->desconectar();
                    $mensaje = 'Conexion cerrada';
                break;

                // Si no se encuentra un valor, simplemente enviamos un mensaje
                default:
                    $mensaje = "Acción no reconocida.";
                break;
            }

        }catch(Exception $e){
            $mensaje = "Error: " . $e->getMessage();
        }


    }
}








//********COMENTARIOS*********//


/*
require_once __DIR__ . '/../config/config.php';

putenv("TNS_ADMIN=" . ORACLE_TNS_ADMIN);

$conn = oci_connect(ORACLE_USER, ORACLE_PASSWORD, ORACLE_SERVICE_NAME);
if (!$conn) {
    $e = oci_error();
    echo "Error en conexión: " . $e['message'];
} else {
    echo "Conexión exitosa";
    oci_close($conn);
}
*/

?>


<!--<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Test conexión Oracle</title>
</head>
<body>
    <h1>Estado de la conexión: <?php echo $mensaje ? $mensaje : "Desconectada"; ?></h1>

    <form method="post">
        <button type="submit" name="accion" value="conectar">Conectar</button>
        <button type="submit" name="accion" value="desconectar">Desconectar</button>
    </form>
</body>
</html>-->
