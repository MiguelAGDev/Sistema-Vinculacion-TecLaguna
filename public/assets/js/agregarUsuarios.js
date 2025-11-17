function mostrarCampos() {
  const tipo = document.getElementById("tipo").value;
  const camposAlumno = document.getElementById("alumno");
  const camposResidente = document.getElementById("residente");
  const camposEgresado = document.getElementById("egresado");

  // Ocultamos ambos primero
  camposAlumno.style.display = "none";
  camposResidente.style.display = "none";
  camposEgresado.style.display = "none";

  // Mostramos según el tipo seleccionado
  if (tipo === "1") {
    camposAlumno.style.display = "block";
  } else if (tipo === "2") {
    camposResidente.style.display = "block";
  }else if(tipo === "3") {
    camposEgresado.style.display = "block";
}
}