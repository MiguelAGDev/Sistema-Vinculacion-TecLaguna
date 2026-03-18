<?php
//si requiero de la sesion
require_once __DIR__ .'/../../public/sesiones.php';
require_once __DIR__ .'/../../database/Conexion.php';
require_once __DIR__.'/../models/Permisos.php';
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

 