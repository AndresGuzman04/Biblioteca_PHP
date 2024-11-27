<?php include('template/cabecera.php');?>

<?php
$txtID=((isset($_POST['txtID']))?$_POST['txtID']:"");
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtDireccion=(isset($_POST['txtDireccion']))?$_POST['txtDireccion']:"";
$txtTelefono=(isset($_POST['txtTelefono']))?$_POST['txtTelefono']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include('bd.php');

$conexion = new ConexionPDO($host, $db, $user, $password);
$conexion->conectar();

$query = "SELECT * FROM clientes";
$statement = $conexion->getConnection()->query($query);
$clientes = $statement->fetchAll(PDO::FETCH_ASSOC);

switch ($accion) {
    case 'Agregar':

        try {
            $query = "INSERT INTO clientes (nombre, direccion, telefono) VALUES (:nombre, :direccion, :telefono)";
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':nombre', $txtNombre);
            $statement->bindParam(':direccion', $txtDireccion);
            $statement->bindParam(':telefono', $txtTelefono);
            $statement->execute();

            // Redireccionar o mostrar un mensaje de éxito
            header("Location: clientes.php");
            exit();
        } catch (PDOException $e) {
            echo "Error al insertar los datos: " . $e->getMessage();
        }

    break;

    case 'Modificar':        

        $query= "UPDATE clientes SET nombre=:nombre, direccion=:direccion, telefono=:telefono  WHERE id=:id";
        $statement = $conexion->getConnection()->prepare($query);
        $statement->bindParam(':id',$txtID);
        $statement->bindParam(':nombre', $txtNombre);
        $statement->bindParam(':direccion', $txtDireccion);
        $statement->bindParam(':telefono', $txtTelefono);
        $statement->execute();


        header("Location:clientes.php");

        break;

        case 'Cancelar':
            header("Location:clientes.php");
            break;
    
        case 'Seleccionar':
            $query = ("SELECT * FROM clientes WHERE id=:id");
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':id',$txtID);
            $statement->execute();
            $libro=$statement->fetch(PDO::FETCH_LAZY);
    
            $txtNombre=$libro['nombre'];
            $txtDireccion=$libro['direccion'];
            $txtTelefono=$libro['telefono'];
    
        break;

        case 'Borrar':
            
            $query=("DELETE FROM clientes WHERE id=:id");
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':id',$txtID);
            $statement->execute();
            
            header("Location:clientes.php");
    
        break;    

    }

    

    

?>

    <div class="col-md-4">

        <div class="card">
            <div class="card-header">
                Datos de Cliente
            </div>
            <div class="card-body">
                
            <form method="POST" action="clientes.php" enctype="multipart/form-data" >

                <div class = "form-group">
                <label >ID:</label>
                <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
                </div>

                <div class = "form-group">
                <label >Nombre:</label>
                <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre">
                </div>

                <div class = "form-group">
                <label >Dirección:</label>
                <input type="text" required class="form-control" value="<?php echo $txtDireccion; ?>" name="txtDireccion" id="txtDireccion" placeholder="Dirección">
                </div>

                <div class = "form-group">
                <label >Telefono:</label>
                <input type="txt" required class="form-control" title="Debes escribir en el siguiente formato 0x0x0x0x, 8 digitos sin guiones ni espacios." pattern="[0-9]{4}[0-9]{4}" value="<?php echo $txtTelefono; ?>" name="txtTelefono" id="txtTelefono" placeholder="Telefono">
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

    <div class="col-md-7">
        
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Telefono</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($clientes as $cliente) { ?>
                <tr>
                    <td><?php echo $cliente['id']; ?></td>
                    <td><?php echo $cliente['nombre']; ?></td>
                    <td><?php echo $cliente['direccion']; ?></td>
                    <td><?php echo $cliente['telefono']; ?></td>
                    <td>

                        <form method="post">

                            <input type="hidden" name="txtID" value="<?php echo $cliente['id']; ?>"/>

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