<?php
require_once __DIR__ . '/../controllers/AdminController.php';
$adminController = new adminController();
      if (session_status() === PHP_SESSION_NONE) {
       session_start();
      }
       $tipo = $_SESSION['id_usuario'] ?? null;
$datosUsuario = $adminController->obtenerUsuarioPorId($tipo);

if (!$datosUsuario) {
    die("No se encontró el usuario.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>

    <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/global.css">
    <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/perfil.css">
    <script src="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/js/perfil.js"></script>
</head>
<body>

<?php  
      require_once __DIR__.'/../controllers/AdminController.php';
      require_once __DIR__.'/../includes/Header.ini.php';
      //require_once __DIR__.'/../../models/Administrador.php';
      
?>

<main class="perfil-wrapper">

    <div class="perfil-banner"></div>

    <div class="perfil-card">

        <div class="perfil-avatar">
            <img src="../../assets/img/user-default.png" alt="Foto de perfil">
        </div>

        <h2 class="perfil-nombre"><?= $datosUsuario['NOMBRE_USUARIO'] ?></h2>
        <p class="perfil-rol">Usuario del sistema</p>

        <form action="actualizar_perfil.php" method="POST" class="perfil-form">

            <input type="hidden" name="id_usuario" value="<?= $datosUsuario['ID_USUARIO'] ?>">

           <?php $adminController->regresarSalidas($datosUsuario);?>

        </form>
       <!-- <?php require_once __DIR__.'/../includes/footer.php'; ?>-->

    </div>
</main>
</body>
</html>
