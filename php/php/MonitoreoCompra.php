<?php
session_start();
include("Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$compras = $conexion->query("
    SELECT *
    FROM ventas
    WHERE id_usuario = $id_usuario
    AND estado IN ('pendiente','asignada','en_camino')
    ORDER BY fecha_solicitud DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de compra</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Monitoreo de mi compra</h1>

    <?php while($compra = $compras->fetch_assoc()){ ?>

        <div class="venta-card">

            <h3>Pedido #<?php echo $compra['id_venta']; ?></h3>

            <p><strong>Fecha de solicitud:</strong> <?php echo $compra['fecha_solicitud']; ?></p>
            <p><strong>Estado actual:</strong> <?php echo $compra['estado']; ?></p>
            <p><strong>Total:</strong> C$<?php echo number_format($compra['total_final'] ?? $compra['total'], 2); ?></p>

            <div class="timeline-pedido">

    <div class="paso completado">
        ✅ Solicitud enviada
        <small><?php echo $compra['fecha_solicitud']; ?></small>
    </div>

    <div class="paso <?php echo in_array($compra['estado'], ['asignada','en_camino','entregada']) ? 'completado' : 'activo'; ?>">
        <?php echo $compra['estado'] == 'pendiente' ? '⏳ Esperando asignación de motorizado' : '🛵 Motorizado asignado'; ?>

        <?php if(!empty($compra['fecha_asignacion'])){ ?>
            <small><?php echo $compra['fecha_asignacion']; ?></small>
        <?php } ?>
    </div>

    <div class="paso <?php echo $compra['estado'] == 'en_camino' ? 'activo' : ($compra['estado'] == 'entregada' ? 'completado' : ''); ?>">
        <?php
        if($compra['estado'] == 'en_camino'){
            echo '🚚 Pedido en camino';
        }else if($compra['estado'] == 'entregada'){
            echo '✅ Pedido enviado';
        }else{
            echo '⚪ En camino';
        }
        ?>

        <?php if(!empty($compra['fecha_en_camino'])){ ?>
            <small><?php echo $compra['fecha_en_camino']; ?></small>
        <?php } ?>
    </div>

    <div class="paso <?php echo $compra['estado'] == 'entregada' ? 'completado' : ''; ?>">
        <?php echo $compra['estado'] == 'entregada' ? '📦 Pedido entregado' : '⚪ Entregado'; ?>

        <?php if(!empty($compra['fecha_entrega'])){ ?>
            <small><?php echo $compra['fecha_entrega']; ?></small>
        <?php } ?>
    </div>

</div>

    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

</body>
</html>