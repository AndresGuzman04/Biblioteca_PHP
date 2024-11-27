<?php include('template/cabecera.php');?>

<?php
$txtID=((isset($_POST['txtID']))?$_POST['txtID']:"");
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include('bd.php');

$conexion = new ConexionPDO($host, $db, $user, $password);
$conexion->conectar();

$query = "SELECT * FROM libros";
$statement = $conexion->getConnection()->query($query);
$libros = $statement->fetchAll(PDO::FETCH_ASSOC);

switch ($accion) {
    case 'Agregar':

        try {
            $query = "INSERT INTO libros (nombre, imagen) VALUES (:nombre, :imagen)";
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':nombre', $txtNombre);

            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->gettimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if ($tmpImagen!="") {

                move_uploaded_file($tmpImagen,"img/".$nombreArchivo);

            }

            $statement->bindParam(':imagen', $nombreArchivo);
            $statement->execute();

            // Redireccionar o mostrar un mensaje de éxito
            header("Location: libros.php");
            exit();
        } catch (PDOException $e) {
            echo "Error al insertar los datos: " . $e->getMessage();
        }

    break;

    case 'Modificar':        

        $query= "UPDATE libros SET nombre=:nombre WHERE id=:id";
        $statement = $conexion->getConnection()->prepare($query);
        $statement->bindParam(':nombre',$txtNombre);
        $statement->bindParam(':id',$txtID);
        $statement->execute();

        if ($txtImagen!="") {

            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->gettimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"img/".$nombreArchivo);

            if (isset($libro["imagen"])&&($libro["imagen"]!="imagen.jpg") ) {
                if (file_exists("img/".$libro["imagen"])) {
                    unlink("img/".$libro["imagen"]);
                }
            }

            $query="UPDATE libros SET imagen=:imagen WHERE id=:id";
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':imagen',$nombreArchivo);
            $statement->bindParam(':id',$txtID);
            $statement->execute();

        }

        header("Location:libros.php");

        break;

        case 'Cancelar':
            header("Location:libros.php");
            break;
    
        case 'Seleccionar':
            $query = ("SELECT * FROM libros WHERE id=:id");
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':id',$txtID);
            $statement->execute();
            $libro=$statement->fetch(PDO::FETCH_LAZY);
    
            $txtNombre=$libro['nombre'];
            $txtImagen=$libro['imagen'];
    
        break;

        case 'Borrar':

            $query=("SELECT imagen FROM libros WHERE id=:id");
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':id',$txtID);
            $statement->execute();
            $libro=$statement->fetch(PDO::FETCH_LAZY);
    
            if (isset($libro["imagen"])&&($libro["imagen"]!="imagen.jpg") ) {
                if (file_exists("img/".$libro["imagen"])) {
                    unlink("img/".$libro["imagen"]);
                }
            }
            
            $query=("DELETE FROM libros WHERE id=:id");
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':id',$txtID);
            $statement->execute();
            
            header("Location:libros.php");
    
        break;    

    }

    

    

?>

    <div class="col-md-4">

        <div class="card">
            <div class="card-header">
                Datos de Libro
            </div>
            <div class="card-body">
                
            <form method="POST" action="libros.php" enctype="multipart/form-data" >

                <div class = "form-group">
                <label >ID:</label>
                <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
                </div>

                <div class = "form-group">
                <label >Nombre:</label>
                <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del Libro">
                </div>

                <div class = "form-group">
                <label >Imagen:</label>

               <br>

                <?php if ($txtImagen!="") {?>

                    <img class="img-thumbnail rounded"  src="img/<?php echo $txtImagen; ?>" width="75" alt="">

                <?php }?>

                <br>

                <?php echo $txtImagen; ?>

                <br>

                <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen">
                </div>      

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" <?php echo($accion=="Seleccionar")?"disabled":"" ?> value="Agregar" class="btn btn-success">Agregar</button>
                    <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":"" ?> value="Modificar" class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":"" ?> value="Cancelar" class="btn btn-info">Cancelar</button>
                </div>

                </form>

            </div>

        </div>

        
    </div>

    <div class="col-md-8">
        
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($libros as $libro) { ?>
                <tr>
                    <td><?php echo $libro['id']; ?></td>
                    <td><?php echo $libro['nombre']; ?></td>
                    <td>

                        <img class="img-thumbnail rounded" src="img/<?php echo $libro['imagen']; ?>" width="75" alt="">

                    </td>
                    <td>

                        <form method="post">

                            <input type="hidden" name="txtID" value="<?php echo $libro['id']; ?>"/>

                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>
                            
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>

                        </form>

                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>

<?php include('template/pie.php');?>