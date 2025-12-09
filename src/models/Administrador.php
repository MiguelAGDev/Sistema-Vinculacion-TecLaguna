<?php
// Se requiere de la clase conexion, ya que este envia query's
/**
 * @requires Conexion.php
 * Esta clase es la que realiza las conexiones directamente con
 * la base de datos, solo tiene constructor, conectar, desconectar
 *  y destructor
 */
require_once __DIR__. '/../../database/Conexion.php';
    
/**
 * Summary of administrador
 * Clase que se ecarga de las operaciones relacionadas con el administrador
 * incluyendo conexion a la base de datos y la gestion de usuarios.
 */
class administrador{
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
            $this->conn = $conn->conectar();
        }

        /**
         * Summary of obtenerUsuarios
         * Muestra todos las columnas visibles que tiene la tabla USUARIOS
         *
         * @return Statement El resultado que se compila en la base 
         * de datos
         */
        

        
        public function obtenerUsuarios (){
            $sql ="SELECT id_usuario, nombre_usuario, correo_usuario, telefono_usuario 
                   FROM usuario";
            $stmt = oci_parse ($this->conn,$sql);
            oci_execute($stmt);
            return $stmt;       
        }

      /**
       * Summary of insertarUsuario
       * @param string $nombre Nombre completo del usuario
       * @param string $correo Correo con el que se inscribira el usuario
       * @param string $telefono Telefono de referencia del usuario
       * @param string $contrasena Contrasena de la cuenta "Sistema Vinculacion"
       * @param string $tipo Tipo de usuario (Empresa, Alumno, Residente o Egresado)
       * @param string $carrera Carrera del alumno (En caso de ser empresa es null)
       * @param arrayString $datosExtra es un arreglo que mantiene cada una de las
       *        caracteristicas unincas de cada usuario, puede representar estos son los casos
       *        -- ALUMNO: ['matricula', 'semestre']
       *        -- RESIDENTE: ['asesor', 'empresa']
       *        -- EGRESAD: ['anio_egreso', 'empleo']
       * @return void
       */

      public function insertarUsuario($nombre,$correo,$telefono,$contrasena,$tipo,$carrera,$datosExtra){
        
        /**
         * @var string $sql Consulta para insertar un usuario y retorna su id
         */
      $sql = "INSERT INTO usuario (
                          nombre_usuario, 
                          correo_usuario, 
                          telefono_usuario, 
                          contrasena_usuario, 
                          activo_usuario, 
                          id_tipo_usuario, 
                          id_carrera, 
                          cv_usuario)
              VALUES 
                         (:nombre, 
                         :correo, 
                         :telefono, 
                         :contrasena, 
                         1, 
                         :id_tipo, 
                         :id_carrera,
                          EMPTY_BLOB())
                         RETURNING id_usuario INTO :id_usuario";


        /**
         * @var string Contraseña hasheada usando password_hash con un algoritmo por defecto
         */
        $hashed = password_hash($contrasena,PASSWORD_DEFAULT);
        /*
         * --password_hash genera un hash seguro y "salteado" (usa un valor aleatorio 
         * llamado salt para evitar que dos contraseñas iguales tengan el mismo hash).
         * 
         * --PASSWORD_DEFAULT indica usar el algoritmo recomendado por PHP, actualmente 
         * bcrypt, y garantiza que el código siga siendo seguro en el futuro sin cambios.
        */

        /**
         * @var Statement $stmt Prepara  una quey para su ejecucion con Oracle
         * Recibe como valor un metodo *oci_parse* que prepara la setencia con
         * el string $sql
         */
        $stmt = oci_parse($this->conn,$sql);
        //$lob2 = oci_new_descriptor($this->conn, OCI_D_LOB);
        
        /**
         * Summary of oci_bind_by_name
         * Vincula el valor de ":columna" por el parametro solicitado 
         * Ej. :nombre -> 'Miguel Angel'
         * 
         * @param Statement $stm
         * @param string $parametro
         * @param mixed $valorParametro
         * @param int $maxlength (Opcional) Tamaño máximo para variables de salida (en bytes).
         * @param int $type (Opcional) Tipo de dato Oracle para la variable.
        */
        oci_bind_by_name($stmt,":nombre",$nombre);
        oci_bind_by_name ($stmt,":correo",$correo);
        oci_bind_by_name ($stmt,":telefono",$telefono);
        oci_bind_by_name($stmt, ":contrasena", $hashed);
        oci_bind_by_name($stmt, ":id_tipo", $tipo);
        oci_bind_by_name($stmt, ":id_carrera", $carrera);
        /**
         * Similar a lo mencionado anteriro mente, pero aqui si reservamos 32 bytes
         * de memoria para el valor que saldra. En este caso guarda el dato que recibies 
         * en el RETURN ... INTO ... y lo guarda en la variable $id_usuario
         */
        oci_bind_by_name($stmt, ":id_usuario", $id_usuario, 32);
        //oci_bind_by_name($stmt, ":cv_usuario", $lob2, -1, OCI_B_BLOB);

        /* If que recibe un metodo oci_execute*/
        echo"$nombre,$correo,$contrasena,$telefono";
        /** Summary of oci_execute
         * 
         * @param Statement $stmt Setencia SQL pereaparada con oci_parse()
         * @param int $mode Modo de ejecucion. el 'OCI_COMMIT_ON_SUCCESS' 
         * realiza un commit si se realizo la consulta
         * 
         */

        if(oci_execute($stmt,OCI_NO_AUTO_COMMIT)){
           //if( $lob2->save($cv))
               // {
                    oci_commit($this->conn);
                    echo "<p style='color:green;'> Usuario insertado </p>";
               //} 
        }else{
             echo "<p style='color:red;'> Usuario no insertado </p>";
        }
        // $lob2->free();
         //oci_free_statement($stmt);
        /** Summary of switch 
         * @param int $tipo recibe un int con el tipo de usuario  e inserta 
         * los datos necesarios de cada una de las tablas.
         * 
         * 1 --> ALUMNO: ['idUsuario','matricula', 'semestre']
         * 2 --> RESIDENTE: ['idUsuario', 'asesor', 'empresa']
         * 3 --> EGRESAD: ['idUsuario', 'anio_egreso', 'empleo']
         * 4 --> EMPRESA: ['idUsuario', 'nombre', 'correo', 'telefono', 'tipo']
        */
        switch ($tipo){
            //************************************  ALUMNO ***********************************// 
            case '1':
               $sql2 = "INSERT INTO alumno (id_usuario, matricula_alumno, semestre_alumno)
                     VALUES (:id_usuario, :matricula_alumno,:semestre_alumno)";

            $stmt2 = oci_parse($this->conn, $sql2);
            
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":matricula_alumno", $datosExtra['matricula']);
            oci_bind_by_name($stmt2, ":semestre_alumno", $datosExtra['semestre']);
            break;

            //***********************************  RESIDENTE *************************************// 
            case 2: 
            $sql2 = "INSERT INTO residente (id_usuario, proyecto_residente, id_asesor, id_empresa)
                     VALUES (:id_usuario,EMPTY_BLOB(),:id_empresa)
                     RETURNING proyecto_residente INTO :proyecto_residente";
            $stmt2 = oci_parse($this->conn, $sql2);
            $lob = oci_new_descriptor($this->conn, OCI_D_LOB);
            
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":proyecto_residente", $lob, -1, OCI_B_BLOB);
            oci_bind_by_name($stmt2, ":id_empresa", $datosExtra['empresa']);
            break;

            //*********************************  EGRESADO *****************************************//
            case 3: 
            $sql2 = "INSERT INTO egresado (id_usuario, anio_egreso, empleo_actual)
                     VALUES      (:id_usuario, :anio_egreso, :empleo_actual)";
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":anio_egreso", $datosExtra['anio_egreso']);
            oci_bind_by_name($stmt2, ":empleo_actual", $datosExtra['empleo']);
            break;
           
            //**************************************  EMPRESA ******************************************//
            case 4: 
            $sql2 = "INSERT INTO empresa ( nombre_empresa,correo_empresa,telefono_empresa,id_usuario,giro_empresa,tamanio_empresa,sector_empresa)
                                  VALUES (:nombre,:correo,:telefono,:id_usuario,:giro_empresa,:tamanio_empresa,:sector_empresa)";
                     
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":nombre", $nombre);
            oci_bind_by_name($stmt2, ":correo", $correo);
            oci_bind_by_name($stmt2, ":telefono", $telefono);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario, 32);
            oci_bind_by_name($stmt2, ":giro_empresa", $giro_empresa, 32);
            oci_bind_by_name($stmt2, ":tamanio_empresa", $tamanio_empresa, 32);
            oci_bind_by_name($stmt2, ":sector_empresa", $sector_empresa, 32);
            break;

        }

       if ($tipo === '2') {
            if (oci_execute($stmt2, OCI_NO_AUTO_COMMIT)) {
                if ($lob->save($datosExtra['proyecto'])) {
                    oci_commit($this->conn);
                    echo "<p style='color:green;'> Usuario y archivo insertados correctamente </p>";
                } else {
                    oci_rollback($this->conn);
                    echo "<p style='color:red;'> Error al guardar el contenido del archivo </p>";
                }
            } else {
                echo "<p style='color:red;'> Error al insertar usuario residente </p>";
            }
        
            $lob->free();
            oci_free_statement($stmt2);
        
        }else {

            if (oci_execute($stmt2, OCI_COMMIT_ON_SUCCESS)) {
                echo "<p style='color:green;'> Usuario insertado </p>";
            } else {
                echo "<p style='color:red;'> Usuario no insertado </p>";
            }
        }

    }

     public function buscarUsuarioPorId($id_usuario) {
        $sql = "SELECT  ID_USUARIO,
                    NOMBRE_USUARIO,
                    CORREO_USUARIO,
                    TELEFONO_USUARIO,
                    ACTIVO_USUARIO,
                    NOMBRE_CARRERA
                FROM usuario u
                LEFT JOIN CARRERA c ON c.ID_CARRERA = u.ID_CARRERA
                WHERE u.ID_USUARIO = :id";

        $stid = oci_parse($this->conn, $sql);

        oci_bind_by_name($stid, ":id", $id_usuario);

        oci_execute($stid);

        $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
        if($row['ACTIVO_USUARIO'] === '1'){
            $row['ACTIVO_USUARIO']='ACTIVO';
        }else{
            $row['ACTIVO_USUARIO']='NO ESTA ACTIVO POR EL MOMENTO';
        }
        return $row ?: false;
    }
    
    public function actUsuarios($id_usuario, $nombre, $correo, $telefono, $activo, $carrera) {

    $sql = "UPDATE usuario
            SET nombre_usuario = :nombre,
                correo_usuario = :correo,
                telefono_usuario = :telefono,
                activo_usuario = :activo,
                id_carrera = :nombre_carrera
            WHERE id_usuario = :id";

    $stmt = oci_parse($this->conn, $sql);

    oci_bind_by_name($stmt, ':nombre', $nombre);
    oci_bind_by_name($stmt, ':correo', $correo);
    oci_bind_by_name($stmt, ':telefono', $telefono);
    oci_bind_by_name($stmt, ':activo', $activo);
    oci_bind_by_name($stmt, ':nombre_carrera', $carrera);
    oci_bind_by_name($stmt, ':id', $id_usuario);

    // Ejecuta sin auto-commit
    if (oci_execute($stmt, OCI_DEFAULT)) {
        oci_commit($this->conn);   
        echo "Cambios realizados";
    } else {
        $e = oci_error($stmt);
        echo "Error al modificar: " . $e['message'];
    }
}
}
?>

