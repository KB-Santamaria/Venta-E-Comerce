<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../Loging.html");
    exit();
}

$id_motorizado = $_SESSION['id_usuario'];

$pedidos = $conexion->query("
    SELECT *
    FROM ventas
    WHERE id_motorizado = $id_motorizado
    AND estado IN ('asignada','en_camino')
    ORDER BY fecha_asignacion DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos asignados</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>

<body>

<div class="ventas-pendientes-container">

    <h1>Pedidos asignados</h1>

    <?php while($pedido = $pedidos->fetch_assoc()){ ?>

        <?php
            $totalPedido = !empty($pedido['total_final'])
                ? $pedido['total_final']
                : $pedido['total'];

            $montoPago = !empty($pedido['monto_pago'])
                ? $pedido['monto_pago']
                : 0;

            $vueltoReal = $montoPago - $totalPedido;

            if($vueltoReal < 0){
                $vueltoReal = 0;
            }
        ?>

        <div class="venta-card">

            <h3>Pedido #<?php echo $pedido['id_venta']; ?></h3>

            <p><strong>Cliente:</strong> <?php echo $pedido['nombre_cliente']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $pedido['telefono']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $pedido['direccion']; ?></p>
            <p><strong>Estado:</strong> <?php echo $pedido['estado']; ?></p>
            <p><strong>Tipo de pago:</strong> <?php echo $pedido['tipo_pago']; ?></p>

            <p>
                <strong>Total a cobrar:</strong>
                C$<?php echo number_format($totalPedido, 2); ?>
            </p>

            <?php if($pedido['tipo_pago'] == 'efectivo'){ ?>

                <div class="alerta-vuelto">
                    💰 Llevar vuelto:
                    C$<?php echo number_format($vueltoReal, 2); ?>
                </div>

                <p>
                    <strong>Cliente paga con:</strong>
                    C$<?php echo number_format($montoPago, 2); ?>
                </p>

            <?php } ?>

            <?php if($pedido['tipo_pago'] == 'deposito'){ ?>

                <div class="alerta-deposito">
                    🏦 Pago por depósito
                </div>

            <?php } ?>

            <?php if($pedido['aplica_descuento'] == 'si'){ ?>

                <div class="alerta-descuento">
                    🏷️ Descuento aplicado:
                    C$<?php echo number_format($pedido['descuento'], 2); ?>

                    <br>

                    <strong>Concepto:</strong>
                    <?php echo $pedido['concepto_descuento']; ?>
                </div>

            <?php } ?>

            <h3>Productos del pedido</h3>

            <?php
            $id_venta = $pedido['id_venta'];

            $productos = $conexion->query("
                SELECT d.*, p.nombre_producto, p.imagen
                FROM detalle_venta d
                INNER JOIN productos p
                ON d.id_producto = p.id_producto
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

            <?php if($pedido['estado'] == 'asignada'){ ?>

                <a class="btn-finalizar-compra"
                   href="IniciarEntrega.php?id=<?php echo $pedido['id_venta']; ?>">
                    Iniciar entrega
                </a>

            <?php } ?>

            <?php if($pedido['estado'] == 'en_camino'){ ?>

                <form action="ConfirmarEntrega.php" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="id_venta" value="<?php echo $pedido['id_venta']; ?>">

                    <label class="label-motorizado">
                        Foto de evidencia
                    </label>

                    <input type="file"
                           name="foto_entrega"
                           accept=".jpg,.jpeg,.png,.webp"
                           required>

                    <textarea name="observacion_entrega"
                              placeholder="Observación de entrega"></textarea>

                    <button type="submit" class="btn-finalizar-compra">
                        Confirmar entrega
                    </button>

                </form>

            <?php } ?>

        </div>

    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

</body>
</html>