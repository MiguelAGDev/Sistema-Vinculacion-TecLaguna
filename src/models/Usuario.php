<?php 
/** Esta clase requiere */
require_once __DIR__. '/../../database/Conexion.php';
require_once __DIR__.'/Permisos.php';
    class usuario {
        /** 
         * Summary of conn
         * @var Conexion $conn 
         * se declara la clase 'conn' que sera un objeto conexion,
         * para conectar y desconectar
         */
        private $conn;
        
        
        /* Summary of __construct
        * Metodo que construye la conexion, simplemente la inicializa
        * dentro del objeto "conn"
        */ 
        public function __construct(){
            $conn= new Conexion();
            $this->conn =$conn->conectar();
        }
        
        /**
         * Summary of validarUsuario
         * @param varchar2 $correo Correo electronico del usuario
         * @param HashContext $contrasena COntrasena ingresada para validar 
         * @return void
         */

        public function validarUsuario ($correo,$contrasena){
            /**
             * @var Query  $sql Recibe un String de una consulta a realiza
             */
            $sql="SELECT *
                    FROM usuario 
                    WHERE correo_usuario = :correo";
            /**
             * @var Statement $stmt Ejecuta un metodo oci_parse que prepara la Query para
             * que oracle la entienda y realuza un Statemente si es valida
             */
            $stmt = oci_parse($this->conn,$sql);

            /** :correo recibe el valor del parametro correo */
            oci_bind_by_name ($stmt,':correo',$correo);

            /**
             * @var Query $sql2 Query que inserta un registro en la tabla sesion
             */
            $sql2 ="INSERT INTO sesion (id_usuario,fecha_inicio,fecha_fin,valido)
                    VALUES (:id_usuario,SYSDATE,NULL,:valido)";
            
            /**
             * @var int $valido Valor inicial de la validez de usaurio
             * 0 = no valido
             * 1 = valido
             */
            $valido = 0; 

            /**If que valida que la query se haya ejecutado */
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
                
        /** 
             * Obtiene la fila con los datos del usuario si existe, en forma de arreglo asociativo
             */
            $row = oci_fetch_assoc($stmt);

            /** 
             * Si se encontró el usuario, se procede a validar la contraseña y permisos
             */
            if ($row) {
                $permisos = 1;

                /**
                 * Verifica si el usuario tiene permiso para iniciar sesión
                 */
                if (Permisos::verificarPermiso($permisos, Permisos::PERMISO_INICIAR_SESION)) {
                    /** 
                     * Obtiene la contraseña hasheada almacenada en la base de datos
                     */
                    $hashGuardado = $row['CONTRASENA_USUARIO'];

                    /**
                     * Verifica si la contraseña ingresada coincide con el hash almacenado
                     */
                    if (password_verify($contrasena, $hashGuardado)) {
                        $valido = 1;

                        /**
                         * Prepara la inserción del registro de sesión válida
                         */
                        $stmt2 = oci_parse($this->conn, $sql2);

                        /**
                         * Asocia el ID del usuario y el valor de validez al insert de sesión
                         */
                        oci_bind_by_name($stmt2, ':id_usuario', $row['ID_USUARIO']);
                        oci_bind_by_name($stmt2, ':valido', $valido);

                        /** 
                         * Ejecuta la inserción del registro de sesión
                         */
                        oci_execute($stmt2);

                        /** 
                         * Redirige al usuario a la página principal después de iniciar sesión exitosamente
                         */
                        header("Location: Main.php");
                    } else {
                        echo "<p style='color:red'> Contraseña incorrecta </p>";
                    }
                } else {
                    echo "<p style='color:red'> No tiene permiso de iniciar sesión </p>";
                }
            }
        } else {
            echo "<p style='color:red'> Usuario no encontrado </p>";
        }

        /**
         * Registra el intento de sesión (válido o no), para controlar accesos
         */
        $stmt2 = oci_parse($this->conn, $sql2);
        oci_bind_by_name($stmt2, ':id_usuario', $row['ID_USUARIO']);
        oci_bind_by_name($stmt2, ':valido', $valido);
        oci_execute($stmt2);
    }

    }
} // FINAL CLASE usuario