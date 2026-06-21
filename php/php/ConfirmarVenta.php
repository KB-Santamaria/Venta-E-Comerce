<?php
session_start();
include("Conexion.php");

$id_venta = $_POST['id_venta'];
$total = $_POST['total'];
$id_motorizado = $_POST['id_motorizado'];

$aplica_descuento = isset($_POST['aplica_descuento']) ? 'si' : 'no';

$descuento = 0;
$concepto_descuento = "NULL";
$total_final = $total;

if($aplica_descuento == 'si'){

    $descuento = !empty($_POST['descuento']) ? $_POST['descuento'] : 0;
    $concepto = $_POST['concepto_descuento'];

    $total_final = $total - $descuento;

    if($total_final < 0){
        $total_final = 0;
    }

    $concepto_descuento = "'" . $concepto . "'";
}

$vuelto_final = 0;

$consultaVenta = $conexion->query("
    SELECT tipo_pago, monto_pago 
    FROM ventas 
    WHERE id_venta = $id_venta
");

$ventaDatos = $consultaVenta->fetch_assoc();

if($ventaDatos['tipo_pago'] == 'efectivo'){
    $vuelto_final = $ventaDatos['monto_pago'] - $total_final;

    if($vuelto_final < 0){
        $vuelto_final = 0;
    }
}

$sql = "
UPDATE ventas SET
    aplica_descuento = '$aplica_descuento',
    descuento = '$descuento',
    concepto_descuento = $concepto_descuento,
    total_final = '$total_final',
    vuelto = '$vuelto_final',
    id_motorizado = '$id_motorizado',
    estado = 'asignada',
    fecha_confirmacion = NOW(),
    fecha_asignacion = NOW()
WHERE id_venta = $id_venta
";

if($conexion->query($sql)){
    header("Location: VentasPendientes.php");
    exit();
}else{
    echo "Error al confirmar venta: " . $conexion->error;
}
?>