function mostrarButtons() {

    const btnCancelar = document.getElementById("cancelar");
    const btnGuardar = document.getElementById("guardar");
    const btnModificar = document.getElementById("modificar");

    // ===== CARRERA =====
    const divCarreraModificar = document.getElementById("divCarreraModificar");
    const divCarreraMostrar = document.getElementById("divCarreraMostrar");

    // ===== ESTATUS =====
    const divEstatusModificar = document.getElementById("divEstatusModificar");
    const divEstatusMostrar = document.getElementById("divEstatusMostrar");

    // Mostrar botones
    btnCancelar.style.display = "block";
    btnGuardar.style.display = "block";
    btnModificar.style.display = "none";

    // Cambiar carrera
    divCarreraModificar.style.display = "block";
    divCarreraMostrar.style.display = "none";

    // Cambiar estatus
    divEstatusModificar.style.display = "block";
    divEstatusMostrar.style.display = "none";
}


// ===== BOTÓN CANCELAR REVIERTE TODO =====
document.getElementById("cancelar").addEventListener("click", () => {

    const btnCancelar = document.getElementById("cancelar");
    const btnGuardar = document.getElementById("guardar");
    const btnModificar = document.getElementById("modificar");

    const divCarreraModificar = document.getElementById("divCarreraModificar");
    const divCarreraMostrar = document.getElementById("divCarreraMostrar");

    const divEstatusModificar = document.getElementById("divEstatusModificar");
    const divEstatusMostrar = document.getElementById("divEstatusMostrar");

    // Ocultar botones
    btnCancelar.style.display = "none";
    btnGuardar.style.display = "none";
    btnModificar.style.display = "block";

    // Revertir carrera
    divCarreraModificar.style.display = "none";
    divCarreraMostrar.style.display = "block";

    // Revertir estatus
    divEstatusModificar.style.display = "none";
    divEstatusMostrar.style.display = "block";
});


 