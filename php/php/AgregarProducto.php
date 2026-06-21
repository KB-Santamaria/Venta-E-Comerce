<?php
include("Conexion.php");

$categorias = $conexion->query("SELECT * FROM categorias");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Ventas.css">
    <title>Agregar Producto</title>
</head>

<body>

<div class="ventas-pendientes-container">

    <h1>Agregar Producto</h1>

    <form action="GuardarProducto.php"
      method="POST"
      enctype="multipart/form-data"
      class="venta-card form-producto">

        <label>Nombre del producto</label>
        <input type="text" name="nombre_producto" required>

        <label>Descripción</label>
        <textarea name="descripcion" required></textarea>

        <label>Precio</label>
        <input type="number" step="0.01" name="precio" required>

        <label>Stock</label>
        <input type="number" name="stock" required>

        <label>Categoría</label>

        <select name="id_categoria" required class="select-motorizado">

            <option value="">
                Seleccione una categoría
            </option>

            <?php while($cat = $categorias->fetch_assoc()){ ?>

                <option value="<?php echo $cat['id_categoria']; ?>">
                    <?php echo $cat['nombre_categoria']; ?>
                </option>

            <?php } ?>

        </select>

        <label>Imagen del producto</label>

        <input type="file"
               name="imagen"
               accept=".jpg,.jpeg,.png"
               required>

        <div class="botones-venta">

            <button type="submit"
                    class="btn-finalizar-compra">
                Guardar Producto
            </button>

        </div>

    </form>

    <div class="carrito-botones">

        <a href="../Inicio.php"
           class="btn-seguir-comprando">
            Volver al inicio
        </a>

    </div>

</div>

</body>
</html>