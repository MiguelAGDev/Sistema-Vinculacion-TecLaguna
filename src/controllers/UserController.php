<?php
require_once __DIR__.'/../models/Usuario.php';
// a lo que entendi aqui se controla metodos que tiene que ver con el usuario como listarlos obtenerlos
//o eliminar y estos son los metodos
class UserController {
    private $usrModel;

    public function __construct() {
         $this-> usrModel= new usuario();
    }

  private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?url=auth/login');
            exit;
        }
    }

    public function index() {
        $this->verificarSesion();
        include __DIR__ . '/../views/usuarios.php';
    }

    public function perfil() {
        $this->verificarSesion();
        include __DIR__ . '/../views/perfil.php';
    }

     public function agregar() {
        $this->verificarSesion();
        include __DIR__ . '/../views/agregarUsuario.php';
    }
    
    public function validarUsuario(){
        if($_SERVER ['REQUEST_METHOD'] === 'POST'){
            $correo = $_POST['correo'];
            $contrasena = $_POST ['contrasena'];
            $this->usrModel->validarUsuario ($correo,$contrasena);
        }
    }  
}

