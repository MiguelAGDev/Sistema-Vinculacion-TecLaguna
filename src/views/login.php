
<?php
    require_once CONTROLLERS_PATH. 'LoginController.php';
    $controlador = new AuthController();
    $resultado = null;
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $controlador->validar();
        }
        if (isset($resultado['sesion_activa']) && $resultado['sesion_activa']){
          header("Location: index.php?url=auth/confirmacion");
          exit;
        }
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Servicios - Inicio de Sesión</title>

   <link rel="stylesheet" href="/assets/css/global.css">
  <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>

  <?php  require_once INCLUDES_PATH.'Header.ini.copy.php'; ?>

  <main>
    <h2>Bienvenido al Portal de Servicios en Línea.</h2>

    <section class="login-card">
      <div class="login-header">
        TECNOLÓGICO NACIONAL DE MÉXICO
      </div>

      <div class="login-body">
        <h3>INICIO DE SESIÓN</h3>

        <form action="index.php?url=auth/validar" method="POST">
          <input type="text" name="correo" id="correo" placeholder="Correo" required />
          <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required />

          <button type="submit" class="btn-primary">ACCEDER</button>
         <button type="button" class="btn-secondary" onclick="window.location.href='index.php?url=admin/agregar&action=register'">
    REGISTRARSE
</button>
        </form>
      </div>
    </section>
  </main>

  <script src="https://kit.fontawesome.com/a2e0d6b8c1.js" crossorigin="anonymous"></script>

  <?php require_once INCLUDES_PATH.'Footer.ini.php'; ?>
</body>
</html>



