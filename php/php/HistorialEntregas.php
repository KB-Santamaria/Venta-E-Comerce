<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../Loging.html");
    exit();
}

$id_motorizado = $_SESSION['id_usuario'];

$entregas = $conexion->query("
    SELECT *
    FROM ventas
    WHERE id_motorizado = $id_motorizado
    AND estado = 'entregada'
    ORDER BY fecha_entrega DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de entregas</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Historial de entregas</h1>

    <?php if($entregas->num_rows == 0){ ?>
        <div class="venta-card">
            <p>No tienes entregas finalizadas.</p>
        </div>
    <?php } ?>

    <?php while($entrega = $entregas->fetch_assoc()){ ?>

        <div class="venta-card">

            <h3>Pedido #<?php echo $entrega['id_venta']; ?></h3>

            <p><strong>Cliente:</strong> <?php echo $entrega['nombre_cliente']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $entrega['telefono']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $entrega['direccion']; ?></p>
            <p><strong>Fecha de entrega:</strong> <?php echo $entrega['fecha_entrega']; ?></p>
            <p><strong>Tipo de pago:</strong> <?php echo $entrega['tipo_pago']; ?></p>
            <p><strong>Total:</strong> C$<?php echo number_format($entrega['total_final'] ?? $entrega['total'], 2); ?></p>

            <?php if($entrega['tipo_pago'] == 'efectivo'){ ?>
                <p><strong>Cliente pagó con:</strong> C$<?php echo number_format($entrega['monto_pago'] ?? 0, 2); ?></p>
                <p><strong>Vuelto entregado:</strong> C$<?php echo number_format($entrega['vuelto'] ?? 0, 2); ?></p>
            <?php } ?>

            <?php if(!empty($entrega['observacion_entrega'])){ ?>
                <p><strong>Observación:</strong> <?php echo $entrega['observacion_entrega']; ?></p>
            <?php } ?>

            <?php if(!empty($entrega['foto_entrega'])){ ?>
                <p><strong>Evidencia de entrega:</strong></p>

                <img class="foto-evidencia-entrega"
                     src="../ImgEntregas/<?php echo $entrega['foto_entrega']; ?>"
                     alt="Evidencia de entrega">
            <?php } ?>

            <h3>Productos entregados</h3>

            <?php
            $id_venta = $entrega['id_venta'];

            $productos = $conexion->query("
                SELECT d.*, p.nombre_producto, p.imagen
                FROM detalle_venta d
                INNER JOIN productos p ON d.id_producto = p.id_producto
                WHERE d.id_venta = $id_venta
            ");

            while($producto = $productos->fetch_assoc()){
            ?>

                <div class="producto-entrega">

                    <img src="../ImgProductos/<?php echo $producto['imagen']; ?>" alt="Producto">

                    <div>
                        <p><strong><?php echo $producto['nombre_producto']; ?></strong></p>
                        <p>Cantidad: <?php echo $producto['cantidad']; ?></p>
                        <p>Subtotal: C$<?php echo number_format($producto['subtotal'], 2); ?></p>
                    </div>

                </div>

            <?php } ?>

        </div>

    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

</body>
</html>