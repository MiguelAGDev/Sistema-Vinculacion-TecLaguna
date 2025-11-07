<?php
//ruta donde se encuentra el wallet de nuestra base de datos
putenv("TNS_ADMIN=C:\\Conexiones\\Oracle\\Wallet_VinculacionInstitutoTecDeLaLag\\Wallet_VinculacionInstitutoTecDeLaLag");


//credenciales
$usuario = "ADMIN"; 
$contrasena = "Abc123456789___"; 
$service_name="vinculacioninstitutotecdelalag_high";

//se ejecuta la conexion y se pasan por parametros los datos 
$conn = oci_connect($usuario, $contrasena, $service_name);
//si resulta fallida nos marca un error
if (!$conn) {
    $e = oci_error();
    echo "Ha surgido un error en la conexión: " . $e['message'];
} else {
//si no nos muestra este msj    
    echo "La conexión se realizó con éxito";
    oci_close($conn);
}

?>
