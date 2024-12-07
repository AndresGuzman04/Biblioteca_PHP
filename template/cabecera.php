
<?php

session_start();

if ($_SESSION['usuario']===null) {
    header('Location: index.php');
}

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Admin</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <?php $url="https://bibliotecaphp-production.up.railway.app" ?>
    
    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="#">Admin<span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="<?php echo $url;?>/dash.php">  Inicio</a>
            <a class="nav-item nav-link" href="<?php echo $url;?>/libros.php">  Libros</a>
            <a class="nav-item nav-link" href="<?php echo $url;?>/clientes.php">  Clientes</a>
            <a class="nav-item nav-link" href="<?php echo $url;?>/alquiler.php">  Alquiler</a>
            <a class="nav-item nav-link" href="<?php echo $url;?>/cerrar.php">  Cerrar</a>
        </div>
    </nav>

    <div class="container">
      <br>
        <div class="row">