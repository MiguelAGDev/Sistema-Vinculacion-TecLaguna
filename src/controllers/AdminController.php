<?php
require_once __DIR__ .'/../models/Administrador.php';

class adminController{
          private $adminModel;

          public function __construct(){
               $this->adminModel= new administrador();
          }

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
}
?>