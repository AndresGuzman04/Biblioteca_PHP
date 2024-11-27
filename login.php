<?php

class Login{
    public $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function login($usuario, $password){
        try {
            $query = "SELECT * FROM usuario WHERE usuario = :usuario AND password = :password";
            $pdo = $this->conexion->getConnection();
            $statement = $pdo->prepare($query);
            $statement->bindParam(':usuario', $usuario);
            $statement->bindParam(':password', $password);
            $statement->execute();

            if ($statement->rowCount() == 1) {
                return true;
            }else{
                return false;
            }

        } catch (PDOException $e) {
            echo "Error en la consulta: ". $e->getMessage();
            return false;
        }
    }

    public function registrar($usuario, $password) {
        try {
            // Verificar si el usuario ya existe
            $queryVerificar = "SELECT * FROM usuario WHERE usuario = :usuario";
            $pdo = $this->conexion->getConnection();
            $statementVerificar = $pdo->prepare($queryVerificar);
            $statementVerificar->bindParam(':usuario', $usuario);
            $statementVerificar->execute();

            if ($statementVerificar->rowCount() > 0) {
                return "El usuario ya existe.";
            }

            // Insertar nuevo usuario
            $query = "INSERT INTO usuario (usuario, password) VALUES (:usuario, :password)";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':usuario', $usuario);
            $statement->bindParam(':password', $password);

            if ($statement->execute()) {
                return "Usuario registrado con éxito.";
            } else {
                return "Error al registrar el usuario.";
            }
        } catch (PDOException $e) {
            echo "Error en el registro: " . $e->getMessage();
            return false;
        }
    }
    
}

?>