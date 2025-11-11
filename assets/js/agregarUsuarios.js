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
  if (tipo === "alumno") {
    camposAlumno.style.display = "block";
  } else if (tipo === "residente") {
    camposResidente.style.display = "block";
  }else if(tipo === "egresado") {
    camposEgresado.style.display = "block";
}
}