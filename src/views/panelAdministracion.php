 <?php 
    require_once CONTROLLERS_PATH.'AuthController.php';
require_once INCLUDES_PATH.'Header.ini.php';
    $controlador = new AuthController();
    $controlador->requireLogin();
    $tipo = $controlador->role();
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administracion</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/panelAdministracion.css">
    <script src="/assets/js/panelAdministracion.js"></script>
    
</head>
<body>
   
 <form name="tipo" id="tipo">

    <!-- ===================== PANEL ADMINISTRADOR ===================== -->
    <main name="administrador" id="administrador"
        style="display: <?= ($tipo == 5 ? 'block' : 'none') ?>;">
        
        <div class="panel-container">
            <h2 class="panel-title">Panel de Administración</h2>
            <p class="panel-sub">Hola Bienvenido</p>

            <!-- USUARIOS -->
            <div class="panel-option" onclick="toggleMenu('menu_admin_usuarios')">Usuarios</div>
            <div class="dropdown" id="menu_admin_usuarios">
                <a href="index.php?url=admin/usuarios">VER USUARIOS</a>
                <br> <br>
                <a href="index.php?url=admin/agregar">AGREGAR USUARIOS</a>
            </div>

            <!-- CONVOCATORIAS -->
            <div class="panel-option" onclick="toggleMenu('menu_admin_conv')">Convocatorias</div>
            <div class="dropdown" id="menu_admin_conv">
                <a href="index.php?url=flyer/search">VER CONVOCATORIAS</a>
                <br><br>
                <a href="index.php?url=main/manage">AUTORIZAR CONVOCATORIAS</a>
            </div>

            <!-- ESTADÍSTICAS -->
            <div class="panel-option" onclick="toggleMenu('menu_admin_est')">Estadísticas</div>
            <div class="dropdown" id="menu_admin_est">
                <a>VER ESTADISTICAS</a>
            </div>
        </div>
    </main>


    <!-- ===================== PANEL USUARIOS / ALUMNOS ===================== -->
    <main name="usuarios" id="usuarios"
        style="display: <?= ($tipo == 1 || $tipo == 2 || $tipo == 3 ? 'block' : 'none') ?>;">
        
        <div class="panel-container">
            <h2 class="panel-title">Panel Usuario</h2>

            <div class="panel-option" onclick="toggleMenu('menu_user_conv')">Convocatorias</div>
            <div class="dropdown" id="menu_user_conv">
                <a href="index.php?url=flyer/search">VER CONVOCATORIAS</a>
            </div>

            <div class="panel-option" onclick="toggleMenu('menu_user_perfil')">Perfil</div>
            <div class="dropdown" id="menu_user_perfil">
                <a href="index.php?url=admin/perfil">VER PERFIL</a>
            </div>
        </div>
    </main>


    <!-- ===================== PANEL EMPRESAS ===================== -->
    <main name="empresas" id="empresas"
        style="display: <?= ($tipo == 4 ? 'block' : 'none') ?>;">
        
        <div class="panel-container">

            <h2 class="panel-title">Panel Empresa</h2>

            <div class="panel-option" onclick="toggleMenu('menu_empresa_conv')">Convocatorias</div>
            <div class="dropdown" id="menu_empresa_conv">
                <a href="index.php?url=flyer/create">SUBIR CONVOCATORIA</a>
                <br><br>
                <a href="index.php?url=flyer/search">VER CONVOCATORIAS</a>
            </div>

        </div>
    </main>

</form>

</body>
</html>