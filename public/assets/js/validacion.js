document.addEventListener("DOMContentLoaded", function() {
    inicializarBotonesFlyers();
});

function inicializarBotonesFlyers() {
    // BOTÓN APROBAR
    document.querySelectorAll(".btn-approve").forEach(btn => {
        btn.addEventListener("click", function() {
            manejarAccionFlyer(this.dataset.id, "aprobar");
        });
    });

    // BOTÓN RECHAZAR
    document.querySelectorAll(".btn-reject").forEach(btn => {
        btn.addEventListener("click", function() {
            manejarAccionFlyer(this.dataset.id, "rechazar");
        });
    });
}

function manejarAccionFlyer(flyerId, accion) {
    if (!flyerId) return;

    const mensaje = accion === "aprobar"
        ? "¿Seguro que deseas aprobar este flyer?"
        : "¿Seguro que deseas rechazar este flyer?";

    if (!confirm(mensaje)) return;

    // Llamar al controlador (sin cambiar la página actual)
    fetch(`main/${accion}/${flyerId}`)
        .then(() => {
            // Recargar para mostrar cambios
            location.reload();
        })
        .catch(() => {
            alert("Error de conexión con el servidor");
        });
}

