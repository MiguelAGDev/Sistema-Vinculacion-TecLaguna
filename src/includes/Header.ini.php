<?php
if (session_status() === PHP_SESSION_NONE){
session_start();
}


// Si no hay usuario logueado, lo tratamos como visitante:
$tipo = $_SESSION['tipo_usuario'] ?? 0; 
?>
<header>
  <img src="../../assets/img/logo/logo-tecnm.png" alt="Logo TecNM" />

  <nav>
    <ul>

      <li><a href="index.php" class="active">Inicio</a></li>

      <?php if ($tipo === 1): // Alumno ?>
        <li><a href="verVacantes.php">Vacantes</a></li>
        <li><a href="misDatos.php">Mis Datos</a></li>

      <?php elseif ($tipo === 2): // Admin ?>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="agregarUsuario.php">Agregar Usuarios</a></li>
        <li><a href="reportes.php">Reportes</a></li>

      <?php elseif ($tipo === 3): // Empresa ?>
        <li><a href="misVacantes.php">Mis Vacantes</a></li>
        <li><a href="postulantes.php">Postulantes</a></li>

      <?php else: // Usuario NO logueado ?>
        <li><a href="login.php">Iniciar Sesión</a></li>
      <?php endif; ?>

      <!-- Botón de Cerrar Sesión (solo si hay usuario logueado) -->
      <?php if ($tipo !== 0): ?>
        <li><a href="logout.php">Cerrar Sesión</a></li>
      <?php endif; ?>

    </ul>
  </nav>
</header>
