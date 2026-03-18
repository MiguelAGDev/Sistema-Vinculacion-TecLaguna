<link rel="stylesheet" href="../../assets/css/menu.css">

<div class="menu-container">

    <div class="menu-title">Panel de Control</div>

    <div class="menu-user">
        👤 <?= htmlspecialchars($nombreUsuario) ?>
    </div>

    <ul class="menu-ul">

        <li><a href="index.php?url=home">🏠 Inicio</a></li>

        <?php if (Permisos::verificarPermiso($permisosUsuario, PERMISO_GESTIONAR_USUARIOS)): ?>
            <li><a href="index.php?url=usuarios/lista">👥 Usuarios</a></li>
            <li><a href="index.php?url=usuarios/crear">➕ Agregar Usuario</a></li>
        <?php endif; ?>

        <?php if (Permisos::verificarPermiso($permisosUsuario, PERMISO_VER_ALUMNOS)): ?>
            <li><a href="index.php?url=alumno/lista">🎓 Alumnos</a></li>
        <?php endif; ?>

        <?php if (Permisos::verificarPermiso($permisosUsuario, PERMISO_VER_RESIDENTES)): ?>
            <li><a href="index.php?url=residente/lista">🏢 Residentes</a></li>
        <?php endif; ?>

        <?php if (Permisos::verificarPermiso($permisosUsuario, PERMISO_VER_EGRESADOS)): ?>
            <li><a href="index.php?url=egresado/lista">📜 Egresados</a></li>
        <?php endif; ?>

        <?php if (Permisos::verificarPermiso($permisosUsuario, PERMISO_REPORTES)): ?>
            <li><a href="index.php?url=reportes/index">📊 Reportes</a></li>
        <?php endif; ?>

        <?php if (Permisos::verificarPermiso($permisosUsuario, PERMISO_CONFIGURACION)): ?>
            <li><a href="index.php?url=configuracion/index">⚙️ Configuración</a></li>
        <?php endif; ?>

        <hr class="menu-divider">

        <li><a href="index.php?url=auth/logout">🚪 Cerrar Sesión</a></li>

    </ul>
</div>

<link rel="stylesheet" href="../../assets/css/content-adjust.css">
