<?php 
require_once __DIR__. '/../../database/conectar.php';
require_once __DIR__. '/../../database/Conexion.php';
        class usuario {
         private $conn;
        
        public function __construct(){
            $c= new Conectar();
          $this->conn =$c->metodoConectar();
        }

        public function validarUsuario ($correo,$contrasena){
            $sql="SELECT *
                  FROM usuario 
                  WHERE correo_usuario = :correo";

            $stmt = oci_parse($this->conn,$sql);
            oci_bind_by_name ($stmt,':correo',$correo);

            if(oci_execute($stmt)){
                $row = oci_fetch_assoc ($stmt); //para recuperar el reglon y poder acceder a a la comlumna;
                if($row){ //si todo sale bien se pasa a verificar;
                    $hashGuardado = $row['CONTRASENA_USUARIO']; //podemos accceder a la contraseña hassheada
                    if(password_verify($contrasena,$hashGuardado)){
                        header("Location: main.php");
                    }else{
                        echo"<p style='color:red'> Contraseña incorrecto </p>";
                        return false;
                    }   
                }
                echo "<p style='color:red'> Usuario no encontrado </p>";
                return false;
            }
            echo "<p style='color:red'> Error en la cosulta </p>";
            return false;
        }
        public function registroSesion($id_correo){
            $sql =$sql="SELECT *
                  FROM usuario 
                  WHERE correo_usuario = :correo";"

        }

    }