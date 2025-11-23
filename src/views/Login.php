<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Servicios - Inicio de Sesión</title>

   <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/global.css">
  <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/login.css">
</head>

<body>

  <?php require_once __DIR__.'/../includes/Header.ini.php'; ?>

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
          <button type="button" class="btn-secondary">REGISTRARSE</button>
        </form>
      </div>
    </section>
  </main>

  <script src="https://kit.fontawesome.com/a2e0d6b8c1.js" crossorigin="anonymous"></script>

  <?php require_once __DIR__.'/../includes/Footer.ini.php'; ?>
</body>
</html>


