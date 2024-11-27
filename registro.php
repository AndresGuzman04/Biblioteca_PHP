<?php

session_start();
include_once 'bd.php'; // Incluye tu clase de conexión
include_once 'login.php'; // Incluye la clase Login

// Configuración de la conexión
$conexion = new ConexionPDO("localhost", "practica_crud", "root", "");
$conexion->conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $usuario = $_POST['user'];
    $password = MD5($_POST['pwd']); // Encriptamos la contraseña con MD5 (puedes cambiarlo a password_hash)

    // Instanciar la clase Login y registrar al usuario
    $login = new Login($conexion);
    $resultado = $login->registrar($usuario, $password);

    if ($resultado === "Usuario registrado con éxito.") {
        echo '<script>
                alert("Usuario registrado exitosamente.");
                window.location.href = "index.php"; // Redirige a login
              </script>';
    } else {
        echo '<script>
                alert("' . $resultado . '");
                window.history.back(); // Regresa al formulario de registro
              </script>';
    }
}

$conexion->desconectar();
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Registro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="m-0 vh-100 row justify-content-center align-items-center">
      <div class="card col-3">
        <div class="card-header">
          Registro de Usuario
        </div>
        <div class="card-body">
          <form action="registro.php" method="POST">
            <div class="form-group">
              <label>Usuario</label>
              <input type="text" class="form-control" name="user" placeholder="Escribe tu Usuario" required>
            </div>
            <div class="form-group">
              <label>Contraseña</label>
              <input type="password" class="form-control" name="pwd" placeholder="Escribe tu Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
            <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>