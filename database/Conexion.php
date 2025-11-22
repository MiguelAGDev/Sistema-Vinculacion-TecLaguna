<?php

require_once __DIR__ . '/../config/config.php';

// 7 de Noviembre 2025, 3:40 PM
class Conexion{


    
    private $conn = null;

    // Constructor de la clase conexion php
    public function __construct(){
        // Establece la variable de entorno para el oracle wallet
        putenv("TNS_ADMIN=".ORACLE_TNS_ADMIN);
    }

    // Metodo conexion, realiza la conexion con la base de datos
    public function conectar(){
        
        // Si la conexion es nula
        if($this->conn == null){
            
            // Se inicializa la conexion
            $this->conn = oci_connect(
                ORACLE_USER, // Usuario de la base de datos
                ORACLE_PASSWORD,// Contrasenha del usuario
                ORACLE_SERVICE_NAME // Nombre del servicio de la base de datos
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


//Esta variable solo mostrar el mensje de 'conexion - realizada o no realizada'
$mensaje = '';

// Creamos un nuevo objeto conexion
$conexion = new Conexion();

//Aqui es todo el proceso
// Verificamos si el formulario fue enviado con metodo POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){

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


