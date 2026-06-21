<?php
session_start();
include("Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$compras = $conexion->query("
    SELECT *
    FROM ventas
    WHERE id_usuario = $id_usuario
    AND estado IN ('entregada','cancelada')
    ORDER BY fecha_solicitud DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis compras</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Historial de compras</h1>

    <?php while($compra = $compras->fetch_assoc()){ ?>

        <div class="venta-card">

            <h3>Pedido #<?php echo $compra['id_venta']; ?></h3>

            <p><strong>Estado:</strong> <?php echo $compra['estado']; ?></p>
            <p><strong>Fecha:</strong> <?php echo $compra['fecha_solicitud']; ?></p>
            <p><strong>Total:</strong> C$<?php echo number_format($compra['total_final'] ?? $compra['total'], 2); ?></p>

            <?php if($compra['estado'] == 'cancelada'){ ?>
                <p><strong>Motivo:</strong> <?php echo $compra['motivo_rechazo']; ?></p>
            <?php } ?>

            <h3>Productos</h3>

            <?php
            $id_venta = $compra['id_venta'];

            $detalles = $conexion->query("
                SELECT d.*, p.nombre_producto
                FROM detalle_venta d
                INNER JOIN productos p ON d.id_producto = p.id_producto
                WHERE d.id_venta = $id_venta
            ");

            while($detalle = $detalles->fetch_assoc()){
            ?>

                <p>
                    <?php echo $detalle['nombre_producto']; ?>
                    x<?php echo $detalle['cantidad']; ?>
                    - C$<?php echo number_format($detalle['subtotal'], 2); ?>
                </p>

            <?php } ?>

        </div>

    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

</body>
</html>