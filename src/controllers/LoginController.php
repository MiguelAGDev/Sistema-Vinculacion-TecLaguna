<?php
//si requiero de la sesion
require_once PUBLIC_PATH.'sesiones.php';
require_once DATABASE_PATH;
require_once MODELS_PATH.'Permisos.php';
class loginController{
   // private $sesion; ya se como se usan las sesiones Sesion::start();
    private $conn;

    public function __construct() {
         $conn= new Conexion();
         $this->conn =$conn->conectar();
    }

    

public static function logout() {
        Sesion ::start();
        Sesion::logout();
        header('Location: index.php?url=auth/login');
        exit;
    }
   
}

 