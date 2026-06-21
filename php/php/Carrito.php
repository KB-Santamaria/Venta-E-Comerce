<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../Loging.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$carrito = $conexion->query("
    SELECT c.*, p.nombre_producto, p.precio, p.imagen, p.stock
    FROM carrito c
    INNER JOIN productos p ON c.id_producto = p.id_producto
    WHERE c.id_usuario = $id_usuario
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi carrito</title>
    <link rel="stylesheet" href="../Inicio.css">
    <link rel="stylesheet" href="../Carrito.css">
</head>
<body>

<div class="carrito-container">

    <h1>Mi carrito</h1>

    <?php while($item = $carrito->fetch_assoc()){ 
        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;
    ?>

        <div class="carrito-item">

            <img src="../ImgProductos/<?php echo $item['imagen']; ?>">

            <div>

    <h3><?php echo $item['nombre_producto']; ?></h3>

    <p>
        Precio: C$<?php echo number_format($item['precio'], 2); ?>
    </p>

    <div class="cantidad-carrito">

        <a href="DisminuirCarrito.php?id=<?php echo $item['id_carrito']; ?>">
            -
        </a>

        <span>
            <?php echo $item['cantidad']; ?>
        </span>

        <?php if($item['cantidad'] < $item['stock']){ ?>

    <a href="AumentarCarrito.php?id=<?php echo $item['id_carrito']; ?>">
        +
    </a>

         <?php } else { ?>

    <span class="btn-deshabilitado">+</span>

<?php } ?>

    </div>

    <p>
        Subtotal: C$<?php echo number_format($subtotal, 2); ?>
    </p>

<?php if($item['cantidad'] >= $item['stock']){ ?>
    <p class="mensaje-stock">
        ⚠ Has alcanzado el stock máximo disponible
    </p>
<?php } ?>


    <a class="btn-eliminar-carrito"
       href="EliminarCarrito.php?id=<?php echo $item['id_carrito']; ?>">
        🗑 Eliminar producto
    </a>

</div>
        </div>

    <?php } ?>

    <h2>Total: C$<?php echo number_format($total, 2); ?></h2>

</div>

<div class="carrito-botones">

    <a href="../Inicio.php" class="btn-seguir-comprando">
        Seguir comprando
    </a>

   <a href="#" class="btn-solicitar-compra" onclick="abrirModalCompra(); return false;">
    Solicitar Compra
   </a>

</div>

<div class="modal-compra" id="modalCompra">

    <div class="modal-compra-contenido">

        <h2>Solicitar compra</h2>

        <form action="GuardarVenta.php" method="POST" enctype="multipart/form-data">

            <input type="text" name="nombre_cliente" placeholder="Nombre completo" required>

            <input type="text" name="telefono" placeholder="Número telefónico" required>

            <textarea name="direccion" placeholder="Dirección exacta" required></textarea>

            <select name="tipo_pago" id="tipoPago" required onchange="cambiarTipoPago()">
                <option value="">Seleccione tipo de pago</option>
                <option value="deposito">Depósito</option>
                <option value="efectivo">Efectivo</option>
            </select>

            <div id="campoDeposito" style="display:none;">
                <label>Comprobante de depósito</label>
                <input type="file" name="comprobante_pago" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div id="campoEfectivo" style="display:none;">
                <input type="number" step="0.01" id="montoPago" name="monto_pago" placeholder="¿Con cuánto paga?" oninput="calcularVuelto()">

                <p id="textoVuelto">
                    Vuelto: C$0.00
                </p>
            </div>

            <input type="hidden" id="totalCompra" value="<?php echo $total; ?>">

            <div class="modal-botones">
                <button type="button" class="btn-cancelar" onclick="cerrarModalCompra()">Cancelar</button>
                <button type="submit" class="btn-confirmar-eliminar">Enviar solicitud</button>
            </div>

        </form>

    </div>

</div>
<script src="../Carrito.js"></script>
</body>
</html>