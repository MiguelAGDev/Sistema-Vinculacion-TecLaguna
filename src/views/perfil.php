<?php
require_once CONTROLLERS_PATH. 'AdminController.php';
require_once CONTROLLERS_PATH.'AuthController.php';

$auth = new AuthController();
$auth->requireLogin();

$adminController = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $adminController->modficarUsuario();
    // Refresco opcional para evitar reenvío de formulario
    header("Location: " . $_SERVER['PHP_SELF'] . (isset($_GET['id']) ? "?id=".$_GET['id'] : ""));
    exit;
}

require_once INCLUDES_PATH.'Header.ini.php';
if (isset($_GET['id'])) {
    $id_busqueda = (int) $_GET['id'];
} else {
    $id_busqueda = $_SESSION['user']['id'] ?? null;
}

if ($id_busqueda === null) { die("Usuario no válido."); }

$datosUsuario = $adminController->obtenerUsuarioPorId($id_busqueda);
if (!$datosUsuario) { die("No se encontró el usuario."); }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?= htmlspecialchars($datosUsuario['NOMBRE_USUARIO'] ?? '') ?></title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/perfil.css">
    <script src="/assets/js/perfil.js"></script>
</head>
<body>


<?php  
  require_once INCLUDES_PATH.'Header.ini.php';
?>


<main class="perfil-wrapper">
    <div class="perfil-banner"></div>

    <div class="perfil-card">
        <div class="perfil-avatar">
            <img src="../../assets/img/user-default.png" alt="Foto de perfil">
        </div>

        <h2 class="perfil-nombre"><?= htmlspecialchars($datosUsuario['NOMBRE_USUARIO'] ?? 'Sin Nombre') ?></h2>
        <p class="perfil-rol">Usuario del sistema</p>

        <form action="" method="POST" class="perfil-form">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($datosUsuario['ID_USUARIO'] ?? '') ?>">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($datosUsuario['NOMBRE_USUARIO'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($datosUsuario['CORREO_USUARIO'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($datosUsuario['TELEFONO_USUARIO'] ?? '') ?>">
            </div>

            <div id="divEstatusMostrar" class="campo">
                <label>Estatus</label> 
                <input type="text" readonly value="<?= ($datosUsuario['ACTIVO_USUARIO'] ?? 0) == 1 ? 'Activo' : 'Inactivo' ?>">
            </div>

            <div id="divEstatusModificar" class="campo" style="display:none;">
                <label for="estatusModificar">Estatus</label>
                <select id="estatusModificar" name="estatus">
                    <option value="1" <?= ($datosUsuario['ACTIVO_USUARIO'] == 1) ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= ($datosUsuario['ACTIVO_USUARIO'] == 0) ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <div id="divCarreraMostrar" class="campo">
                <label>Carrera</label>
                <input type="text" readonly value="<?= htmlspecialchars($datosUsuario['NOMBRE_CARRERA'] ?? 'No asignada') ?>">
            </div>

            <div id="divCarreraModificar" class="campo" style="display:none;">
                <label for="carreraModificar">Carrera</label>
                <select id="carreraModificar" name="carrera">
                    <?php 
                    $carreras = [
                        1 => "Licenciatura en Administración", 2 => "Ingeniería en Eléctrica",
                        3 => "Ingeniería en Electrónica", 4 => "Ingeniería en Energías Renovables",
                        5 => "Ingeniería en Gestión Empresarial", 6 => "Ingeniería en Sistemas Computacionales",
                        7 => "Ingeniería Industrial", 8 => "Ingeniería Mecánica",
                        9 => "Ingeniería Mecatrónica", 10 => "Ingeniería Química"
                    ];
                    foreach ($carreras as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($datosUsuario['ID_CARRERA'] ?? '') == $id ? 'selected' : '' ?>>
                            <?= $nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="perfil-botones">
                <button type="button" class="btn-primario" id="modificar" onclick="mostrarButtons()">Actualizar Perfil</button>
                <button type="button" class="btn-cancelar" id="cancelar" style="display:none;" onclick="location.reload()">Cancelar</button>
                <button type="submit" class="btn-primario" id="guardar" name="guardar" style="display:none;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</main>

</body>
</html>