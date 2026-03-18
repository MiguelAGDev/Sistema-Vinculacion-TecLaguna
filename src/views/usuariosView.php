<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
   <!-- Estilos globales "../../assets/css/global.css"-->
  <link rel="stylesheet" href="/assets/css/global.css">

  <!-- Estilos del formulario -->
  <link rel="stylesheet" href="/assets/css/usuarios.css">
</head>
<body>

<?php 
    require_once __DIR__.'/../controllers/AdminController.php';
    require_once __DIR__.'/../includes/Header.ini.php';
    require_once __DIR__.'/../controllers/LoginController.php';
    $controlador = new AuthController();
    $resultado = null;
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $controlador->validar();
        }
        if (isset($resultado['sesion_activa']) && $resultado['sesion_activa']){
          header("Location: index.php?url=auth/confirmacion");
          exit;
        }
    $controlador = new AdminController();
?>
<main>
<div class="tabla-contenedor">
    <div class="tabla-titulo">Lista de Usuarios</div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
        <?= $u = $controlador->mostrarUsuarios();?>
        </tbody>
    </table>
    
</div>
        </main>
<!--<?php require_once __DIR__.'/../includes/Footer.php';?>-->
</body>
</html>


