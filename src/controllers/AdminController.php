<?php
require_once __DIR__.'/../models/Administrador.php';

class AdminController{
          private $adminModel;

          public function __construct(){
               $this->adminModel= new administrador();
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

    public function agregar() {
        $this->verificarSesion();
        include __DIR__ . '/../views/agregarUsuario.php';
    }

    public function flyer() {
        $this->verificarSesion();
        include __DIR__ . '/../views/flyers.php';
    }

    public function perfil() {
        $this->verificarSesion();
        include __DIR__ . '/../views/perfil.php';
    }


    

     //metodos con logica
          public function mostrarUsuarios() {
          $usuarios = $this->adminModel->obtenerUsuarios();
          
          $html = '';

          while ($row = oci_fetch_assoc($usuarios)) {
               $html .= "<tr>
                         <td><a href='perfil.php? id=" . urlencode($row['ID_USUARIO']) . "'>" . htmlspecialchars($row['NOMBRE_USUARIO']) . "</a></td>
                              <td>" . htmlspecialchars($row['CORREO_USUARIO']) . "</td>
                              <td>" . htmlspecialchars($row['TELEFONO_USUARIO']) . "</td>
                         </tr>";
          }

          return $html;
          }

          public function insertarUsuario (){
          if($_SERVER ['REQUEST_METHOD']==='POST'){
               $nombre = $_POST['nombre'];
               $correo = $_POST ['correo'];
               $telefono = $_POST ['telefono'];
               $contrasena = $_POST ['contrasena'] ?? 'default123';
               $tipo = $_POST ['tipo'];
               $carrera = $_POST ['carrera'];

               $datosExtra = [];
               
               if($tipo === '1'){
                    $datosExtra['matricula'] = $_POST['matricula'];
                    $datosExtra['semestre'] = $_POST['semestre'];
               }else if($tipo === '2'){
                    $datosExtra['proyecto'] = file_get_contents($_FILES ['archivo']['tmp_name']);
                    $datosExtra['asesor'] = $_POST['asesor'];
                    $datosExtra['empresa'] = $_POST['empresa'];
               }else if ($tipo === '3'){
                    $datosExtra['anio_egreso'] = $_POST['anio_egreso'];
                    $datosExtra['empleo'] = $_POST['empleo'];
               }
               
               $insertar=$this->adminModel->insertarUsuario($nombre,$correo,$telefono,$contrasena,$tipo,$carrera,$datosExtra);
          }

     }
      public function obtenerUsuarioPorId($id) {
        return $this->adminModel->buscarUsuarioPorId($id);
    }

      public function regresarSalidas($datosUsuario) {
    echo '
        <input type="hidden" name="id_usuario" value="' . htmlspecialchars($datosUsuario['ID_USUARIO']) . '">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre"
                   value="' . htmlspecialchars($datosUsuario['NOMBRE_USUARIO']) . '" required>
        </div>

        <div class="campo">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo"
                   value="' . htmlspecialchars($datosUsuario['CORREO_USUARIO']) . '" required>
        </div>

        <div class="campo">
            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" name="telefono"
                   value="' . htmlspecialchars($datosUsuario['TELEFONO_USUARIO']) . '">
        </div>

        <div class="campo">
            <label for="estatus">Estatus</label>
            <input type="text" id="estatus" name="estatus"
                   value="' . htmlspecialchars($datosUsuario['ACTIVO_USUARIO']) . '">
        </div>

        <div class="campo">
            <label for="carrera">Carrera</label>
            <input type="text" id="carrera" name="carrera"
                   value="' . htmlspecialchars($datosUsuario['NOMBRE_CARRERA']) . '">
        </div>

        <div class="perfil-botones">
            <button type="submit" class="btn-guardar">Guardar Cambios</button>
            <button type="button" class="btn-cancelar" onclick="window.location.href=\'usuarios.php\'">Cancelar</button>
        </div>';
          }
          
      }

?>