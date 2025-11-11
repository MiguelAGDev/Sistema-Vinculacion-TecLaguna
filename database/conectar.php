
<?php
/**
 * Clase Conectar
 * 
 * Esta clase actúa como una capa intermedia de seguridad entre las demás partes del sistema 
 * y la clase principal de conexión a la base de datos (Conexion.php).
 * 
 * Su objetivo es evitar que otras capas accedan directamente a la clase de conexión 
 * y proporcionar métodos controlados para abrir y cerrar la conexión con la base de datos Oracle.
 * 
 * Ubicación sugerida: /src/models/Conectar.php
 */

require_once __DIR__ . '/Conexion.php'; // Se incluye el archivo que contiene la clase principal de conexión

class Conectar {

    /**
     * @var Conexion $conexion
     * Propiedad privada que almacena la instancia de la clase Conexion.
     * Esto garantiza que solo esta clase tenga acceso directo a la conexión.
     */
    private $conexion;

    /**
     * Constructor de la clase.
     * 
     * Crea una nueva instancia de la clase Conexion.
     * De esta manera, el objeto Conectar siempre tendrá acceso a los métodos
     * conectar() y desconectar() de forma encapsulada y segura.
     */
    public function __construct() {
        $this->conexion = new Conexion();  // Se instancia la conexión a la base de datos
    }

    /**
     * Método: metodoConectar()
     * 
     * Establece la conexión con la base de datos.
     * Devuelve el recurso o identificador de conexión activa (en este caso, de Oracle).
     * 
     * @return resource|false Retorna la conexión activa si fue exitosa, o false si falló.
     */
    public function metodoConectar() {
        return $this->conexion->conectar();  // Devuelve el enlace activo de conexión
        
    }

    /**
     * Método: metodoDesconectar()
     * 
     * Cierra la conexión previamente abierta con la base de datos.
     * Este método ayuda a liberar recursos y mantener la seguridad del sistema.
     * 
     * @return void
     */
    public function metodoDesconectar() {
        return $this->conexion->desconectar();  // Finaliza la conexión
    }
}
?>

