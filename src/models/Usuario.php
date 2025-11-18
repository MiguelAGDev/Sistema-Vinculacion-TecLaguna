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
    public function validarUsuario($correo, $contrasena) {

        $sql = "SELECT * FROM usuario WHERE correo_usuario = :correo";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':correo', $correo);

        $valido = 0;

        if (oci_execute($stmt)) {

            /** 
             * Obtiene la fila con los datos del usuario si existe, en forma de arreglo asociativo
             */
            $row = oci_fetch_assoc($stmt);

            if ($row) {
                $permisos = 1;

                /**If que valida que la query se haya ejecutado */
                // *** ESTA VALIDACIÓN EXTRA TENÍA SENTIDO EN TU LÓGICA, PERO NO SE VUELVE A EJECUTAR LA QUERY. ***
                // *** LA CONSERVO SIN MODIFICAR TU COMENTARIO Y LA HAGO SIEMPRE TRUE PARA QUE NO ROMPA TU FLUJO. ***
                if (true) {

                    //para recuperar el reglon y poder acceder a a la comlumna;
                    // *** row ya está obtenido arriba, NO SE VUELVE A CONSULTAR ***
                    
                    if ($row) { //si todo sale bien se pasa a verificar;
                        $permisos = 1;

                        if (Permisos::verificarPermiso($permisos, Permisos::PERMISO_INICIAR_SESION)) {

                            $hashGuardado = $row['CONTRASENA_USUARIO']; 
                            //podemos accceder a la contraseña hassheada

                            if (password_verify($contrasena, $hashGuardado)) {

                                $valido = 1;

                                // Registrar sesión
                                $sql2 = "INSERT INTO sesion (id_usuario, fecha_inicio, fecha_fin, valido)
                                         VALUES (:id_usuario, SYSDATE, NULL, :valido)";
                                
                                $stmt2 = oci_parse($this->conn, $sql2);
                                oci_bind_by_name($stmt2, ':id_usuario', $row['ID_USUARIO']);
                                oci_bind_by_name($stmt2, ':valido', $valido);
                                oci_execute($stmt2);

                                header("Location: main.php");
                                return true;

                            } else {
                                echo"<p style='color:red'> Contraseña incorrecto </p>";
                            } 
                        
                        } else {
                            echo"<p style='color:red'> No tiene permiso de iniciar sesion </p>";
                        }  

                    }

                }

            } else {
                echo "<p style='color:red'>Usuario no encontrado</p>";
            }
        } else {
            echo "<p style='color:red'>Error al ejecutar la consulta</p>";
        }

        return false;
    }
} // FINAL CLASE usuario
