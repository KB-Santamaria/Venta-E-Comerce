<?php
include("Conexion.php");

$id = $_GET['id'];

$producto = $conexion->query("SELECT * FROM productos WHERE id_producto = $id")->fetch_assoc();
$categorias = $conexion->query("SELECT * FROM categorias");

if(isset($_POST['actualizar'])){

    $nombre = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_categoria = $_POST['id_categoria'];

    $sql = "UPDATE productos SET
            nombre_producto = '$nombre',
            descripcion = '$descripcion',
            precio = '$precio',
            stock = '$stock',
            id_categoria = '$id_categoria'
            WHERE id_producto = $id";

    if($conexion->query($sql)){
        header("Location: ../Inicio.php");
        exit();
    }else{
        echo "Error al actualizar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>

    <link rel="stylesheet" href="../Ventas.css">
</head>

<body>

<div class="ventas-pendientes-container">

    <h1>Editar Producto</h1>

    <form method="POST" class="venta-card form-producto">

        <label>Nombre del producto</label>

        <input type="text"
               name="nombre_producto"
               value="<?php echo $producto['nombre_producto']; ?>"
               required>

        <label>Descripción</label>

        <textarea name="descripcion" required><?php echo $producto['descripcion']; ?></textarea>

        <label>Precio</label>

        <input type="number"
               step="0.01"
               name="precio"
               value="<?php echo $producto['precio']; ?>"
               required>

        <label>Stock</label>

        <input type="number"
               name="stock"
               value="<?php echo $producto['stock']; ?>"
               required>

        <label>Categoría</label>

        <select name="id_categoria"
                required
                class="select-motorizado">

            <?php while($cat = $categorias->fetch_assoc()){ ?>

                <option value="<?php echo $cat['id_categoria']; ?>"

                    <?php
                    if($cat['id_categoria'] == $producto['id_categoria']){
                        echo "selected";
                    }
                    ?>>

                    <?php echo $cat['nombre_categoria']; ?>

                </option>

            <?php } ?>

        </select>

        <div class="botones-venta">

            <button type="submit"
                    name="actualizar"
                    class="btn-finalizar-compra">

                Actualizar Producto

            </button>

        </div>

    </form>

    <div class="carrito-botones">

        <a href="../Inicio.php"
           class="btn-seguir-comprando">

            Volver

        </a>

    </div>

</div>

</body>
</html>