<?php 
require_once __DIR__. '/../../database/conectar.php';
require_once __DIR__. '/../../database/Conexion.php';
require_once __DIR__.'/Permisos.php';
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
             $sql2 ="INSERT INTO sesion (id_usuario,fecha_inicio,fecha_fin,valido)
                     VALUES (:id_usuario,SYSDATE,NULL,:valido)";
            $valido = 0; 

            if(oci_execute($stmt)){
                $row = oci_fetch_assoc ($stmt); //para recuperar el reglon y poder acceder a a la comlumna;
                if($row){ //si todo sale bien se pasa a verificar;
                    $permisos = 1;
                    if(Permisos::verificarPermiso($permisos,Permisos::PERMISO_INICIAR_SESION )){
                         $hashGuardado = $row['CONTRASENA_USUARIO']; //podemos accceder a la contraseña hassheada
                            if(password_verify($contrasena,$hashGuardado)){
                                $valido=1;
                                $stmt2= oci_parse($this->conn,$sql2);
                                        oci_bind_by_name ($stmt2,':id_usuario',$row['ID_USUARIO']);
                                        oci_bind_by_name ($stmt2,':exito_sesion',$exito_sesion);
                                        oci_execute($stmt2);
                                    header("Location: main.php");
                            }else{
                                echo"<p style='color:red'> Contraseña incorrecto </p>";
                            } 
                      }else{
                             echo"<p style='color:red'> No tiene permiso de iniciar sesion </p>";
                      }  
                }
            }else{
                 echo "<p style='color:red'> Usuario no encontrado </p>";
                 
            }
            $stmt2= oci_parse($this->conn,$sql2);
                    oci_bind_by_name ($stmt2,':id_usuario',$row['ID_USUARIO']);
                    oci_bind_by_name ($stmt2,':valido',$valido);
                    oci_execute($stmt2);
        }


        
    }