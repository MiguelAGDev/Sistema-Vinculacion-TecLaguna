<?php
require_once __DIR__ . '/../config/config.php';

putenv("TNS_ADMIN=" . ORACLE_TNS_ADMIN);

$conn = oci_connect(ORACLE_USER, ORACLE_PASSWORD, ORACLE_SERVICE_NAME);
if (!$conn) {
    $e = oci_error();
    echo "Error en conexión: " . $e['message'];
} else {
    echo "Conexión exitosa";
    oci_close($conn);
}
