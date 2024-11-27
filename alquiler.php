<?php include('template/cabecera.php');?>

<?php
$txtID=((isset($_POST['txtID']))?$_POST['txtID']:"");
$libroID=((isset($_POST['libroID']))?$_POST['libroID']:"");
$clienteID=((isset($_POST['clienteID']))?$_POST['clienteID']:"");
$txtAccion=(isset($_POST['txtAccion']))?$_POST['txtAccion']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include('bd.php');

$conexion = new ConexionPDO($host, $db, $user, $password);
$conexion->conectar();

$query = "SELECT * FROM clientes";
$statement = $conexion->getConnection()->query($query);
$clientes = $statement->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM libros";
$statement = $conexion->getConnection()->query($query);
$libros = $statement->fetchAll(PDO::FETCH_ASSOC);

$query = "select alquiler.id as id, libros.nombre as libro, libros.imagen as imagen, 
clientes.nombre as cliente, clientes.id as idCliente,clientes.direccion as direccion, 
clientes.telefono as telefono , fecha, accion 
FROM alquiler
inner join clientes on alquiler.id_cliente	= clientes.id
inner join libros on alquiler.id_libro	= libros.id";
$statement = $conexion->getConnection()->query($query);
$alquileres = $statement->fetchAll(PDO::FETCH_ASSOC);

switch ($accion) {
    case 'Agregar':

        try {
            $query = "INSERT INTO alquiler (id_libro, id_cliente, fecha, accion) VALUES (:libro, :cliente, :fecha, :accion)";
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':libro', $libroID);
            $statement->bindParam(':cliente', $clienteID);
            $fecha= new DateTime();
            $fechaString = $fecha->format('Y-m-d H:i:s');
            $statement->bindParam(':fecha', $fechaString);
            $statement->bindParam(':accion', $txtAccion);
            $statement->execute();

            // Redireccionar o mostrar un mensaje de éxito
            header("Location: alquiler.php");
            exit();
        } catch (PDOException $e) {
            echo "Error al insertar los datos: " . $e->getMessage();
        }

    break;

    case 'Modificar':        

        $query= "UPDATE alquiler SET id_libro=:libro, id_cliente=:cliente, fecha=:fecha, accion=:accion  WHERE id=:id";
        $statement = $conexion->getConnection()->prepare($query);
        $statement->bindParam(':id',$txtID);
        $statement->bindParam(':libro', $libroID);
        $statement->bindParam(':cliente', $clienteID);
        $fecha= new DateTime();
        $fechaString = $fecha->format('Y-m-d H:i:s');
        $statement->bindParam(':fecha', $fechaString);
        $statement->bindParam(':accion', $txtAccion);
        $statement->execute();

        header("Location:alquiler.php");

        break;

        case 'Cancelar':
            header("Location:alquiler.php");
            break;
    
        case 'Seleccionar':
            $query = ("SELECT * FROM alquiler WHERE id=:id");
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':id',$txtID);
            $statement->execute();
            $alquiler=$statement->fetch(PDO::FETCH_LAZY);

            $libroID=$alquiler['id_libro'];
            $clienteID=$alquiler['id_cliente'];
            $txtAccion=$alquiler['accion'];
    
        break;

        case 'Borrar':
            
            $query=("DELETE FROM alquiler WHERE id=:id");
            $statement = $conexion->getConnection()->prepare($query);
            $statement->bindParam(':id',$txtID);
            $statement->execute();
            
            header("Location:alquiler.php");
    
        break;    

    }

    

    

?>

    <div class="col-md-3">

        <div class="card">
            <div class="card-header">
                Datos Alquiler de libros
            </div>
            <div class="card-body">
                
            <form method="POST" action="alquiler.php" enctype="multipart/form-data" >

                <div class = "form-group">
                <label >ID:</label>
                <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
                </div>

                <div class = "form-group">
                <label >Libro:</label>
                <select class="form-control form-control-sm" name="libroID">
                <option selected require>Seleccione...</option>
                <?php
                    foreach($libros as $libro){
                        // echo "<option value='".$libro['id']."' >".$libro['nombre']."</option>";
                        if($libro['id']== $alquiler['id_libro']){
                            echo "<option value='".$libro['id']."' selected>".$libro['nombre']."</option>";
                        }else{
                            echo "<option value='".$libro['id']."' >".$libro['nombre']."</option>";
                        }
                    }
                ?>
                </select>    
                </div>

                <div class = "form-group">
                <label >Cliente:</label>
                <select class="form-control form-control-sm" name="clienteID">
                <option selected require>Seleccione...</option>
                <?php
                    foreach($clientes as $cliente){
                        // echo "<option value='".$cliente['id']."' >".$cliente['nombre']." - ".$cliente['telefono']."</option>";
                        if($cliente['id']== $alquiler['id_cliente']){
                            echo "<option value='".$cliente['id']."' selected>".$cliente['nombre']." - ".$cliente['telefono']."</option>";
                        }else{
                            echo "<option value='".$cliente['id']."' >".$cliente['nombre']." - ".$cliente['telefono']."</option>";
                        }
                    }
                ?>
                </select>
                </div>

                <div class = "form-group">
                <label >Accion/Movimiento:</label>
                <select class="form-control form-control-sm" name="txtAccion">
                <option selected require>Seleccione...</option>
                <option value="Salida" <?php if ($txtAccion == "Salida") {echo "selected";}?> 
                    >Salida</option>
                <option value="Entrada" <?php if ($txtAccion == "Entrada") {echo "selected";}?>
                    >Entrada</option>';
                </select>
                </div>

                <div class="form-group"  aria-label="">
                    <button type="submit" name="accion" <?php echo($accion=="Seleccionar")?"disabled":"" ?> value="Agregar" class="btn btn-success form-control">Agregar</button>
                    <p></p>
                    <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":"" ?> value="Modificar" class="btn btn-warning form-control">Modificar</button>
                    <p></p>
                    <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":"" ?> value="Cancelar" class="btn btn-info form-control">Cancelar</button>
                </div>

                </form>

            </div>

        </div>

        
    </div>

    <script>
        $(function () {
        $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <div class="col-md-9">
        
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Libro</th>
                    <th>Imagen</th>
                    <th>Cliente</th>
                    <th>Telefono</th>
                    <th>Fecha</th>
                    <th>Accion</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($alquileres as $alquiler) { ?>
                <tr>
                    <td><?php echo $alquiler['id']; ?></td>
                    <td><?php echo $alquiler['libro']; ?></td>
                    <td>
                        <img class="img-thumbnail rounded" src="img/<?php echo $alquiler['imagen']; ?>" width="75" alt="">
                    </td>
                    <td data-toggle="tooltip" data-placement="top" title="<?php echo 'Codigo: '.$alquiler['idCliente'].' | Direccion: '.$alquiler['direccion'] ?>"><?php echo $alquiler['cliente']; ?></td>
                    <td><?php echo $alquiler['telefono']; ?></td>
                    <td><?php echo $alquiler['fecha']; ?></td>
                    <td><?php echo $alquiler['accion']; ?></td>
                    <td>
                        <form method="post">

                            <input type="hidden" name="txtID" value="<?php echo $alquiler['id']; ?>"/>

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