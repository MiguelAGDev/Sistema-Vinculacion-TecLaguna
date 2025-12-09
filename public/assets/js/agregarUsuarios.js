function mostrarCampos() {
  const tipo = parseInt(document.getElementById("tipo").value);

  const camposAlumno = document.getElementById("alumno");
  const camposResidente = document.getElementById("residente");
  const camposEgresado = document.getElementById("egresado");
  const camposEmpresa = document.getElementById ("cempresa");
  const opcional = document.getElementById("opcional");

  // Ocultar todo
  camposAlumno.style.display = "none";
  camposResidente.style.display = "none";
  camposEgresado.style.display = "none";
  camposEmpresa.style.display = "none";
  opcional.style.display = "none";

  // Mostrar según el tipo
  switch (tipo) {
    case 1: // Alumno
      opcional.style.display = "block";
      camposAlumno.style.display = "block";
      break;

    case 2: // Residente
      camposResidente.style.display = "block";
      opcional.style.display = "block";
      break;

    case 3: // Egresado
      camposEgresado.style.display = "block";
      opcional.style.display = "block";
      break;

    case 4: // Empresa
      camposEmpresa.style.display = "block";
      opcional.style.display = "none";
      break;
  }
}

