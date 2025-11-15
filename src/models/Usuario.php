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

             $sql2 ="INSERT INTO sesion (id_usuario,fecha_inicio,fecha_fin,exito_inicio)
                     VALUES (:id_usuario,SYSDATE,NULL,:exito_sesion)";
            $exito_sesion = 0;                              
            if(oci_execute($stmt)){
                $row = oci_fetch_assoc ($stmt); //para recuperar el reglon y poder acceder a a la comlumna;
                if($row){ //si todo sale bien se pasa a verificar;
                    $hashGuardado = $row['CONTRASENA_USUARIO']; //podemos accceder a la contraseña hassheada
                    if(password_verify($contrasena,$hashGuardado)){
                        $exito_sesion=1;
                        $stmt2= oci_parse($this->conn,$sql2);
                                oci_bind_by_name ($stmt2,':id_usuario',$row['ID_USUARIO']);
                                oci_bind_by_name ($stmt2,':exito_sesion',$exito_sesion);
                                oci_execute($stmt2);
                               header("Location: main.php");
                    }else{
                        echo"<p style='color:red'> Contraseña incorrecto </p>";
                    }   
                }
            }else{
                 echo "<p style='color:red'> Usuario no encontrado </p>";
            }
            
            $stmt2= oci_parse($this->conn,$sql2);
                    oci_bind_by_name ($stmt2,':id_usuario',$row['ID_USUARIO']);
                    oci_bind_by_name ($stmt2,':exito_sesion',$exito_sesion);
                    oci_execute($stmt2);
        }
       

    }