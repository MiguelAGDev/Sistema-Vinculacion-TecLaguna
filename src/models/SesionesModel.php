<?
require_once __DIR__. '/../../database/Conexion.php';

class SesionesModel {

        private $conn;
        private $id;
        private $ip;
        private $fecha_inicio;
        private $fecha_fin;
        private $valido;

        
        
        /* Summary of __construct
        * Metodo que construye la conexion, simplemente la inicializa
        * dentro del objeto "conn"
        */ 
        public function __construct(){
            $this->conn =$conn->conectar();

        }


        
}