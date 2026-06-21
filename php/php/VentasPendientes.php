<?php
session_start();
include("Conexion.php");

$ventas = $conexion->query("
    SELECT *
    FROM ventas
    WHERE estado = 'pendiente'
    ORDER BY fecha_solicitud DESC
");

$motorizados = $conexion->query("
    SELECT id_usuario, nombre_completo
    FROM usuarios
    WHERE id_rol = 2
");
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>Ventas pendientes</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>

<div class="ventas-pendientes-container">

    <h1>Ventas pendientes</h1>

    <?php while($venta = $ventas->fetch_assoc()){ ?>

        <div class="venta-card">

            <h3>Pedido #<?php echo $venta['id_venta']; ?></h3>

            <p><strong>Cliente:</strong> <?php echo $venta['nombre_cliente']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $venta['telefono']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $venta['direccion']; ?></p>
            <p><strong>Pago:</strong> <?php echo $venta['tipo_pago']; ?></p>

            <?php if($venta['tipo_pago'] == 'efectivo'){ ?>
                <p><strong>Paga con:</strong> C$<?php echo number_format($venta['monto_pago'], 2); ?></p>
                <p><strong>Vuelto:</strong>C$<?php echo number_format($venta['vuelto'] ?? 0, 2); ?></p>
            <?php } ?>

            <?php if($venta['tipo_pago'] == 'deposito' && !empty($venta['comprobante_pago'])){ ?>
                <p><strong>Comprobante:</strong></p>
                <img src="../ImgComprobantes/<?php echo $venta['comprobante_pago']; ?>" width="180">
            <?php } ?>

            <h3>Productos</h3>

            <?php
            $id_venta = $venta['id_venta'];

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

            <hr>

            <p><strong>Total original:</strong> C$<?php echo number_format($venta['total'], 2); ?></p>

            <form action="ConfirmarVenta.php" method="POST">

                <input type="hidden" name="id_venta" value="<?php echo $venta['id_venta']; ?>">
                <input type="hidden" name="total" value="<?php echo $venta['total']; ?>">

                <label class="check-descuento">
    <input type="checkbox"
           name="aplica_descuento"
           value="si"
           onchange="mostrarDescuento(this, <?php echo $venta['id_venta']; ?>)">
    Aplicar descuento
</label>

<div id="descuento-<?php echo $venta['id_venta']; ?>"
     class="bloque-descuento"
     style="display:none;">

    <div class="campos-descuento">

        <input type="number"
               step="0.01"
               name="descuento"
               placeholder="Descuento C$"
               oninput="calcularTotalFinal(<?php echo $venta['id_venta']; ?>, <?php echo $venta['total']; ?>)">

        <textarea name="concepto_descuento"
                  placeholder="Concepto del descuento"></textarea>

    </div>

    <p class="total-final">
        <strong>Total a pagar:</strong>
        C$<span id="totalFinal-<?php echo $venta['id_venta']; ?>">
            <?php echo number_format($venta['total'], 2); ?>
        </span>
    </p>

</div>

<label class="label-motorizado">
    🛵 Motorizado asignado
</label>

<select name="id_motorizado"
        class="select-motorizado"
        required>
    <option value="">Seleccione motorizado</option>

    <?php
    $motorizados->data_seek(0);
    while($moto = $motorizados->fetch_assoc()){
    ?>
        <option value="<?php echo $moto['id_usuario']; ?>">
            <?php echo $moto['nombre_completo']; ?>
        </option>
    <?php } ?>
</select>

                <button type="submit" class="btn-finalizar-compra">
                    Confirmar pedido
                </button>
                <button type="button"
        class="btn-rechazar-compra"
        onclick="abrirModalRechazo(<?php echo $venta['id_venta']; ?>)">
    Rechazar compra
</button>
            </form>

        </div>

    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

<script>
function mostrarDescuento(check, idVenta){
    const bloque = document.getElementById("descuento-" + idVenta);

    if(check.checked){
        bloque.style.display = "block";
    }else{
        bloque.style.display = "none";
    }
}
</script>

<script>

function calcularTotalFinal(idVenta, total){
    const bloque = document.getElementById("descuento-" + idVenta);
    const inputDescuento = bloque.querySelector("input[name='descuento']");
    const textoTotal = document.getElementById("totalFinal-" + idVenta);

    let descuento = parseFloat(inputDescuento.value);

    if(isNaN(descuento)){
        descuento = 0;
    }

    let totalFinal = total - descuento;

    if(totalFinal < 0){
        totalFinal = 0;
    }

    textoTotal.textContent = totalFinal.toFixed(2);
}
function abrirModalRechazo(idVenta){
    document.getElementById("idVentaRechazo").value = idVenta;
    document.getElementById("modalRechazo").classList.add("activo");
}

function cerrarModalRechazo(){
    document.getElementById("modalRechazo").classList.remove("activo");
}
</script>

<div class="modal-rechazo" id="modalRechazo">
    <div class="modal-rechazo-contenido">

        <h2>Rechazar compra</h2>

        <form action="RechazarVenta.php" method="POST">

            <input type="hidden" name="id_venta" id="idVentaRechazo">

            <p>Indique el motivo del rechazo:</p>

            <textarea
    name="motivo_rechazo"
    minlength="10"
    required>
</textarea>

            <div class="modal-botones">
                <button type="button" class="btn-cancelar" onclick="cerrarModalRechazo()">
                    Cancelar
                </button>

                <button type="submit" class="btn-confirmar-rechazo">
                    Confirmar rechazo
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>