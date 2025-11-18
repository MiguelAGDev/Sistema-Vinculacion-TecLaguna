<?php
require_once __DIR__ . '/../controllers/AdminController.php';

if (!isset($_GET['id'])) {
    die("No se recibió el ID del usuario.");
}

$id_usuario = $_GET['id'];

// Llamar al controlador para obtener los datos
$adminController = new adminController();
$datosUsuario = $adminController->obtenerUsuarioPorId($id_usuario);

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

    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/perfil.css">
</head>
<body>

<?php require_once __DIR__.'/../includes/header.php'; 
      require_once __DIR__.'/../../controllers/AdminController.php';
      require_once __DIR__.'/../../models/Administrador.php';
      $adminModel = new administrador();
      $adminController = new adminController();
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

           <?php $adminController->regresarSalidas();?>

        </form>
        <?php require_once __DIR__.'/../includes/footer.php'; ?>

    </div>
</main>



</body>
</html>
