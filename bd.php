<?php

class ConexionPDO {

    private $host;
    private $db;
    private $user;
    private $password;
    private $port;
    public $conexion;

    public function __construct($host, $db, $user, $password, $port) {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }

    public function conectar() {
        try {
            $this->conexion = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db}",
                $this->user,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }

    public function desconectar() {
        $this->conexion = null;
    }
}
?>




$host="autorack.proxy.rlwy.net";
$db="railway";
$user="root";
$password="UsqVZvzYVymunoQimGcgKcqOYMVCVmNw";

?>