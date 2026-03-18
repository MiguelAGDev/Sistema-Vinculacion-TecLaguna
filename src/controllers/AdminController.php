<?php
require_once __DIR__.'/../models/Administrador.php';

class AdminController{
          private $adminModel;

    /**
     * Summary of __construct
     * Inicializa el modelo de administrador
     */      
    public function __construct(){
               $this->adminModel= new administrador();
    }

      /*private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?url=auth/login');
            exit;
        }
    }*/
      
        //metodos que llaman a las vistas
     public function usuarios() {
        include __DIR__ . '/../views/usuarios.php';
    }

    //metodo para mostrar la vista de gestion de flyers
    public function manage() {
        include __DIR__ . '/../views/adminFlyerManageView.php';
    }
    
    //metodo para mostrar la vista de agregar usuario
    public function agregar() {
        include __DIR__ . '/../views/agregarUsuarioView.php';
    }

    /*  Método para mostrar la vista de perfil  */
    public function perfil() {
        include __DIR__ . '/../views/perfil.php';
    }

     //metodos con logica
          public function mostrarUsuarios() {
          $usuarios = $this->adminModel->obtenerUsuarios();
          
          $html = '';

          // Generar filas de tabla para cada usuario
         while ($row = oci_fetch_assoc($usuarios)) {
                $html .= 
                    "<tr>
                    <td><a href='index.php?url=admin/perfil&id=" . urlencode($row['ID_USUARIO']) . "'>" . htmlspecialchars($row['NOMBRE_USUARIO']) . "</a></td>
                    <td>" . htmlspecialchars($row['CORREO_USUARIO']) . "</td>
                    <td>" . htmlspecialchars($row['TELEFONO_USUARIO']) . "</td>
                    </tr>";
                    }

          return $html;
          }

          /*  Método para insertar un nuevo usuario  */
          public function insertarUsuario (){
          if($_SERVER ['REQUEST_METHOD']==='POST'){
               $nombre = $_POST['nombre'];
               $correo = $_POST ['correo'];
               $telefono = $_POST ['telefono'];
               $contrasena = $_POST ['contrasena'] ?? 'default123';
               $tipo = $_POST ['tipo'];
               $carrera = $_POST ['carrera'];
              // $cv = file_get_contents($_FILES['curriculum']['tmp_name']);

               $datosExtra = [];
               
               if($tipo === '1'){
                    $datosExtra['matricula'] = $_POST['matricula'];
                    $datosExtra['semestre'] = $_POST['semestre'];
               }else if($tipo === '2'){
                    $datosExtra['proyecto'] = file_get_contents($_FILES ['archivo_proyecto']['tmp_name']);
                    $datosExtra['empresa'] = $_POST['empresa'];
               }else if ($tipo === '3'){
                    $datosExtra['anio_egreso'] = $_POST['anio_egreso'];
                    $datosExtra['empleo'] = $_POST['empleo'];
               }else if ($tipo === '4'){
                     $datosExtra['tamanio_empresa'] = $_POST ['tamanio_empresa'];
                     $datosExtra['giro_empresa'] = $_POST ['giro_empresa'];
                     $datosExtra['sector_empresa'] = $_POST ['sector_empresa'];
               }  
               $insertar=$this->adminModel->insertarUsuario($nombre,$correo,$telefono,$contrasena,$tipo,$carrera,$datosExtra);
          }
     }

    /*  Método para obtener los datos de un usuario por ID  */
      public function obtenerUsuarioPorId($id) {
        return $this->adminModel->buscarUsuarioPorId($id);
    }

    /*  Método para modificar los datos de un usuario  */
    public function modficarUsuario (){
            if (isset($_POST['guardar'])) {

        $id_usuario = $_POST['id_usuario'];
        $nombre     = $_POST['nombre'];
        $correo     = $_POST['correo'];
        $telefono   = $_POST['telefono'];
        $activo     = $_POST['estatus'];
        $carrera    = $_POST['carrera'];

        $resultado = $this->adminModel->actUsuarios(
            $id_usuario,
            $nombre,
            $correo,
            $telefono,
            $activo,
            $carrera
        );

        if ($resultado) {
           // echo "<script>alert('Cambios guardados correctamente');</script>";
        } else {
           // echo "<script>alert('Error al actualizar');</script>";
        }
       }
    }
    
}
?>