<?php
require_once __DIR__. '/../models/Usuario.php';
// a lo que entendi aqui se controla metodos que tiene que ver con el usuario como listarlos obtenerlos
//o eliminar y estos son los metodos
class UsersController {
    private $usrModel;

    public function __construct() {
         $this-> usrModel= new usuario();
    }

    public function validarUsuario(){
        if($_SERVER ['REQUEST_METHOD'] === 'POST'){
            $correo = $_POST['correo'];
            $contrasena = $_POST ['contrasena'];
            $this->usrModel->validarUsuario ($correo,$contrasena);
        }
    }

    
}
?>
