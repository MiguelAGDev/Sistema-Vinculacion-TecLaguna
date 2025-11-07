<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Servicios - Inicio de Sesión</title>
  <link rel="stylesheet" href="../../assets/css/global.css" />
  <link rel="stylesheet" href="../../assets/css/login.css" />
</head>
<body>

  <!-- HEADER -->
  <header>
    <img src="../../assets/img/logo/logo-tecnm.png" alt="Logo TecNM" />
    <nav>
      <ul>
        <li><a href="" class="active">Inicio</a></li>
        <li><a href="#">Servicios</a></li>
        <li><a href="#">Contacto</a></li>
         <li><a href="Usuarios.html">Usuarios</a></li>
      </ul>
    </nav>
  </header>

  <!-- MAIN -->
  <main>
    
    <h2>Bienvenido al Portal de Servicios en Línea.</h2>

    <section class="login-card">
      <div class="login-header">
        TECNOLÓGICO NACIONAL DE MÉXICO
      </div>
      <div class="login-body">
        <h3>INICIO DE SESIÓN</h3>
        <form action="validar_login.php" method="POST">
          <input type="text" name="usuario" placeholder="Usuario" required />
          <input type="password" name="password" placeholder="Contraseña" required />
          <button type="submit" class="btn-primary">ACCEDER</button>
          <button type="button" class="btn-secondary">RECUPERAR CONTRASEÑA</button>
        </form>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer>
    <p>© 2025 Tecnológico Nacional de México - Portal de Servicios</p>
    <div class="redes">
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </footer>

  
  <!-- FontAwesome para íconos -->
  <script src="https://kit.fontawesome.com/a2e0d6b8c1.js" crossorigin="anonymous"></script>
</body>
</html>