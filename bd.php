<?php 

class ConexionPDO{

    private $host;
    private $db;
    private $user;
    private $password;
    public $conexion;

    public function __construct($host, $db, $user, $password)
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
    }

    public function conectar(){
        try {
            $opciones = array (
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            $this->conexion = new PDO(
                'mysql:host='.$this->host.';dbname='.$this->db,
                $this->user,
                $this->password,
                $opciones
            );
        } catch (PDOException $e) {
            echo "ERROR DE CONEXION: ".$e->getMessage();
            die();
        }
    }

    public function getConnection(){
        return $this->conexion;
    }

    public function desconectar(){
        $this->conexion = null;
        #echo "Base de datos desconetada";
    }

}

$host="localhost";
$db="practica_crud";
$user="root";
$password="";

?>