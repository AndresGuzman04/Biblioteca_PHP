<?php

include("template/cabecera.php"); 

?>

<div class="col-md-12">
                    
        <div class="jumbotron">
            <h1 class="display-3">Bienvenido <?php echo $_SESSION['usuario']; ?></h1>
            <p class="lead">Vamos a administrar nuestros libros en el sitio web</p>
            <hr class="my-2">
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="libros.php" role="button">Administrar libros</a>
            </p>
        </div>

    </div>


    <?php

include("template/pie.php"); 

?>