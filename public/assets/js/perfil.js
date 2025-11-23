 function mostrarButtons(){
     const botonCancelar = document.getElementById("cancelar");
     const botonGuardar = document.getElementById("guardar");
     const botonModificar = document.getElementById("modificar");

     /*botonCancelar.style.display = "none";
     botonGuardar.style.display = "none";
     botonModificar.style.display = "block";*/

        botonCancelar.style.display = "flex";
        botonGuardar.style.display = "flex";
        botonModificar.style.display = "none";
 }