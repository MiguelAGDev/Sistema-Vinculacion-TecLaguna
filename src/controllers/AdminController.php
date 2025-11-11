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
                    <td>" . htmlspecialchars($row['NOMBRE_USUARIO']) . "</td>
                    <td>" . htmlspecialchars($row['CORREO_USUARIO']) . "</td>
                    <td>" . htmlspecialchars($row['TELEFONO_USUARIO']) . "</td>
                  </tr>";
    }

    return $html;
    }
    public function insertarUsuario() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $contrasena = $_POST['contrasena'] ?? 'default123';
        $tipo = $_POST['tipo'];
        $carrera = $_POST['carrera'];

        $extra = [];
        if ($tipo === 'alumno') {
            $extra['matricula'] = $_POST['matricula'];
            $extra['semestre'] = $_POST['semestre'];
        } elseif ($tipo === 'residente') {
            $extra['proyecto'] = $_POST['proyecto'];
            $extra['asesor'] = $_POST['asesor'];
            $extra['empresa'] = $_POST['empresa'];
        } elseif ($tipo === 'egresado') {
            $extra['anio_egreso'] = $_POST['año_egreso'];
            $extra['empleo'] = $_POST['empleo'];
        }

        $id = $this->adminModel->crearUsuario(
            $nombre, $correo, $telefono, $contrasena,
            ucfirst($tipo), $carrera, $extra
        );

        if ($id) {
            echo "<script>alert('Usuario creado con ID $id');</script>";
        } 
    
        
    }
}
}
?>