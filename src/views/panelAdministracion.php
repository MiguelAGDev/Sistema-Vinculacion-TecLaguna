<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/global.css">
    <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/panelAdministracion.css">
    <script src="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/js/panelAdministracion.js"></script>
    
</head>
<body>
    <?php 
    require_once __DIR__.'/../controllers/AdminController.php';
    require_once __DIR__.'/../includes/Header.ini.php';
   if (session_status() === PHP_SESSION_NONE) {
    session_start();
   }
    $controlador = new AdminController();
    $tipo = $_SESSION['id_tipo_usuario'] ?? null;
  ?>
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
            </div>

            <!-- CONVOCATORIAS -->
            <div class="panel-option" onclick="toggleMenu('menu_admin_conv')">Convocatorias</div>
            <div class="dropdown" id="menu_admin_conv">
                <a href="index.php?url=admin/main">VER CONVOCATORIAS</a>
                <a href="index.php?url=admin/adminFlyersManageView">AUTORIZAR CONVOCATORIAS</a>
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
                <a href="index.php?url=admin/main">VER CONVOCATORIAS</a>
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
                <p href="index.php?url=admin/flyerCreateView">SUBIR CONVOCATORIA</p>
                <p href="index.php?url=admin/main">VER CONVOCATORIAS</p>
            </div>

        </div>
    </main>

</form>

<!--<?php require_once __DIR__.'/../includes/Footer.php';?>-->
</body>
</html>