<?php
require_once CONTROLLERS_PATH. 'AdminController.php';
require_once CONTROLLERS_PATH.'AuthController.php';


$controlador = new AuthController();
$controlador->requireLogin();

$adminController = new AdminController();
require_once INCLUDES_PATH.'Header.ini.php';
if (isset($_GET['id'])) {
    $tipo = (int) $_GET['id'];   // perfil desde link
} else {
    $tipo = $_SESSION['user']['id'] ?? null; // perfil propio
}

if ($tipo === null) {
    die("Usuario no válido.");
}

$datosUsuario = $adminController->obtenerUsuarioPorId($tipo);
$u = $datosUsuario ?? [];


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

    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/perfil.css">
    <script src="/assets/js/perfil.js"></script>
</head>
<body>

<?php  
require_once  require_once INCLUDES_PATH.'Header.ini.php';
?>

<main class="perfil-wrapper">

    <div class="perfil-banner"></div>

    <div class="perfil-card">

        <div class="perfil-avatar">
            <img src="../../assets/img/user-default.png" alt="Foto de perfil">
        </div>

        <h2 class="perfil-nombre"><?= htmlspecialchars($datosUsuario['NOMBRE_USUARIO'] ?? '') ?></h2>
        <p class="perfil-rol">Usuario del sistema</p>

        <form action="" method="POST" class="perfil-form">

    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($datosUsuario['ID_USUARIO'] ?? '') ?>">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre"
               value="<?= htmlspecialchars($datosUsuario['NOMBRE_USUARIO'] ?? '') ?>" required>
    </div>

    <div class="campo">
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo"
               value="<?= htmlspecialchars($datosUsuario['CORREO_USUARIO'] ?? '') ?>" required>
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono"
               value="<?= htmlspecialchars($datosUsuario['TELEFONO_USUARIO'] ?? '') ?>">
    </div>

    <!-- ===== ESTATUS MOSTRAR ===== -->
    <div id="divEstatusMostrar" class="campo">
        <label for="estatusMostrar">Estatus</label> 
        <input type="text" id="estatusMostrar" readonly 
               value="<?= $datosUsuario['ACTIVO_USUARIO'] == 1 ? 'Activo' : 'Inactivo' ?>">
    </div>

    <!-- ===== ESTATUS MODIFICAR ===== -->
    <div id="divEstatusModificar" class="campo" style="display:none;">
    <label for="estatusModificar">Estatus</label>
    <select id="estatusModificar" name="estatus">
        <option value="1" <?= ($datosUsuario['ACTIVO_USUARIO'] ?? '') === 1 ? 'selected' : '' ?>>
            Activo
        </option>
        <option value="0" <?= ($datosUsuario['ACTIVO_USUARIO'] ?? '') !== 0 ? 'selected' : '' ?>>
            Inactivo
        </option>
    </select>
</div>



    <!-- ===== CARRERA MOSTRAR ===== -->
    <div id="divCarreraMostrar" class="campo">
        <label for="carreraActual">Carrera</label>
        <input type="text" id="carreraActual" readonly
               value="<?= htmlspecialchars($datosUsuario['NOMBRE_CARRERA'] ?? '') ?>">
    </div>

    <!-- ===== CARRERA MODIFICAR ===== -->
    <div id="divCarreraModificar" class="campo" style="display:none;">
        <label for="carreraModificar">Carrera</label>
        <pre>
<?php var_dump($datosUsuario['ID_CARRERA']); ?>
</pre>

        <select id="carreraModificar" name="carrera">
            <option value="1" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 1 ? 'selected' : '' ?>>Licenciatura en Administración</option>
            <option value="2" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 2 ? 'selected' : '' ?>>Ingeniería en Eléctrica</option>
            <option value="3" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 3 ? 'selected' : '' ?>>Ingeniería en Electrónica</option>

            <option value="4" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 4 ? 'selected' : '' ?>>
               Ingeniería en Energias Renovables
            </option>

           <option value="5" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 5 ? 'selected' : '' ?>>
               Ingeniería en Gestion Empresarial
            </option>
            <option value="6" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 6 ? 'selected' : '' ?>>
               Ingeniería en Sistemas Computacionales
            </option>

            <option value="7" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 7 ? 'selected' : '' ?>>Ingeniería Industrial</option>
            <option value="8" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 8 ? 'selected' : '' ?>>Ingeniería Mecánica</option>
            <option value="9" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 9 ? 'selected' : '' ?>>Ingeniería Mecatrónica</option>
            <option value="10" <?= ($datosUsuario['ID_CARRERA'] ?? '') == 10 ? 'selected' : '' ?>>Ingeniería Química</option>
        </select>
    </div>


    <div class="perfil-botones">
        <button type="button" class="btn-guardar" id="modificar" onclick="mostrarButtons()">Actualizar Perfil</button>
        <button type="button" class="btn-cancelar" id="cancelar" style="display:none;">Cancelar</button>
        <button type="submit" class="btn-guardar" id="guardar" name="guardar" style="display:none;">Guardar Cambios</button>

        <?php 
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])){
            $adminController->modficarUsuario();
            } ?>
    </div>

</form>

</div>
</main>

</body>
</html>

