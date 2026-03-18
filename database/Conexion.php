<?php

require_once __DIR__.'/../config/Config.php';
// 7 de Noviembre 2025, 3:40 PM
/**
 * Summary of Conexion
 * Clase Conexion, se encarga de realizar la conexion con la base de datos Oracle
 */
class Conexion{
    
    private $conn = null;

    // Constructor de la clase conexion php
    /**
     * Summary of __construct
     * @return void constructor default de la clase Conexion, inicializa las variables clave para la conexion
     */
    public function __construct(){
        // Establece la variable de entorno para el oracle wallet
        putenv("TNS_ADMIN=".ORACLE_TNS_ADMIN);
        putenv("PATH=" . getenv("PATH") . PATH_SEPARATOR .ORACLE_INSTANT_CLIENT);
        putenv(ORACLE_LD_LIBRARY_PATH);
        putenv(ORACLE_SSL_CERT_FILE);
    }

    // Metodo conexion, realiza la conexion con la base de datos
    /**
     * Summary of conectar
     * @return resource Devuelve el recurso de conexion a la base de datos Oracle
     * @throws Exception Lanza una excepcion si no se puede conectar a la base de datos
     */
    public function conectar(){
        
        // Si la conexion es nula
        if($this->conn == null){
            
            // Se inicializa la conexion
            $this->conn = oci_connect(
                ORACLE_ADMIN, // Usuario de la base de datos
                ORACLE_PASSWORD,// Contrasenha del usuario
                ORACLE_SERVICE_NAME
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
    /**
     * Summary of desconectar
     * @return void Cierra la conexion a la base de datos Oracle si existe
     */
    public function desconectar(){

        // Si existe una conexion
        if($this->conn != null){

            // Cierra la conexion
            oci_close($this->conn);

            //Setea como nulla la conexion
            $this->conn = null;
        }
    }

    /**
     * Summary of __destruct
     * @return void Destructor de la clase Conexion, cierra la conexion si existe
     */
    public function __destruct(){

        $this->desconectar();
    }
}

