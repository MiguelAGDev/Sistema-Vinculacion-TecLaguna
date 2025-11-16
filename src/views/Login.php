
<!--!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/login.css" />
</head>
<body>
    <h1>Página de Login</h1>
    <form method="POST" action="index.php?url=auth/loginPost">
        <input type="text" name="username" placeholder="Usuario" required />
        <input type="password" name="password" placeholder="Contraseña" required />
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
-->
<?php 
    require_once __DIR__.'/../controllers/UserController.php';
    require_once __DIR__.'/../includes/Header.ini.php';
    $controlador = new UsersController();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Servicios - Inicio de Sesión</title>
  <link rel="stylesheet" href="../assets/css/global.css" />
  <link rel="stylesheet" href="../assets/css/login.css" />
</head>

<body>

  <!-- MAIN -->
  <main>
    
    <h2>Bienvenido al Portal de Servicios en Línea.</h2>

    <section class="login-card">
      <div class="login-header">
        TECNOLÓGICO NACIONAL DE MÉXICO
      </div>
      <div class="login-body">
        <h3>INICIO DE SESIÓN</h3>
        <form method="POST">
          <input type="text" name="correo" id="correo" placeholder="Correo" required />
          <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required />

          <button type="submit" class="btn-primary">ACCEDER</button>
          <?php $controlador->validarUsuario();?>
          <button type="button" class="btn-secondary">REGISTRARSE</button>
         
        </form>
      </div>
    </section>
  </main>
  <!-- FontAwesome para íconos --> 
  <script src="https://kit.fontawesome.com/a2e0d6b8c1.js" crossorigin="anonymous"></script>
  <?php require_once __DIR__.'/../includes/Footer.ini.php'; ?>
</body>
</html> 

