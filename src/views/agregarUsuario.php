<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Usuario</title>

  <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/global.css">
  <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/agregarUsuarios.css">
   <!-- Script -->
  <script src="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/js/agregarUsuarios.js"></script>
</head>
<body>

  <?php 
    require_once __DIR__.'/../controllers/AdminController.php';
    require_once __DIR__.'/../includes/Header.ini.php';
    $controlador = new AdminController();
  ?>
  
  <main>
    <section class="form-container">
      <h1>Registrar Usuario</h1>

      <form action="" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>

        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" placeholder="Correo institucional" required>

        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="Ej. 8711234567" required>

        <label for="telefono">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena" placeholder="" required>

        <label for="tipo">Tipo de Usuario</label>
        <select id="tipo" name="tipo" required onchange="mostrarCampos()">
          <option value="">Seleccione un tipo</option>
          <option value="1">Alumno</option>
          <option value="2">Residente</option>
          <option value="3">Egresado</option>
          <option value="4">Empresa</option>
        </select>

        <!-- Si no es una empresa -->
      <div id="opcional" style="display:none;">
        <label for="Curriculum" >Curriculum</label>
        <input type="file" name="curriculum" id="curriculum">

        <label for="carrera">Carrera</label>
        <select id="carrera" name="carrera">
          <option value="">Seleccione una carrera</option>
          <option value="1">Licenciatura en Administración</option>
          <option value="2">Ingeniería en Eléctrica</option>
          <option value="3">Ingeniería en Electrónica</option>
          <option value="4">Ingeniería en Energías Renovables</option>
          <option value="5">Ingeniería en Gestión Empresarial</option>
          <option value="6">Ingeniería en Sistemas Computacionales</option>
          <option value="7">Ingeniería Industrial</option>
          <option value="8">Ingeniería Mecánica</option>
          <option value="9">Ingeniería Mecatrónica</option>
          <option value="10">Ingeniería Química</option>
        </select>

      </div>

       
         <!-- Campos Alumno -->
        <div id="alumno" style="display: none;" >
          <label>Matricula</label>
          <input type="text" id="matricula" name="matricula" placeholder="Matrícula">

          <label>Semestre</label>
          <input type="text" id="semestre" name="semestre" placeholder="Ej. Ago-Dic 2024">
        </div>

        <!-- Campos Residente -->
        <div id="residente" style="display: none;">
         <label for="proyecto">Proyecto</label>
         <input type="file" name="archivo_proyecto" id="proyecto">


          <!-- <input type="text" id="proyecto" name="proyecto" placeholder="Proyecto"> -->

          <label for="asesor">Asesor</label>
          <select id="asesor" name="asesor" >
            <option value="">Seleccione un asesor</option>
            <option value="1">Asesor 1</option>
            <option value="2">Asesor 2</option>
            <option value="3">Asesor 3</option>
            <option value="4">Asesor 4</option>
            <option value="5">Asesor 5</option>
            <option value="6">Asesor 6</option>
            <option value="7">Asesor 7</option>
          </select>

          <label for="empresa">Empresa</label>
          <select id="empresa" name="empresa" >
            <option value="">Seleccione una empresa</option>
            <option value="1">Empresa 1</option>
            <option value="2">Empresa 2</option>
            <option value="3">Empresa 3</option>
            <option value="4">Empresa 4</option>
            <option value="5">Empresa 5</option>
            <option value="6">Empresa 6</option>
            <option value="7">Empresa 7</option>
          </select>
        </div>

        <!-- Campos Egresado -->
        <div id="egresado" style="display: none;">
          <label>Año de Egreso</label>
          <input type="text" id="anio_egreso" name="anio_egreso" pattern="\d{4}"placeholder="Ej. 2025">

          <label>Empleo</label>
          <input type="text" id="empleo" name="empleo" placeholder="Empleo">
        </div>
          <button type="submit">Registrar</button>
          <?php $controlador->insertarUsuario(); ?>  

      </form>           
    </section>
  </main>
    <!--<?php require_once __DIR__.'/../includes/Footer.php';?>-->
 
</body>
</html>
 