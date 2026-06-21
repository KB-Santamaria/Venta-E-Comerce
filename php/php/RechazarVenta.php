<?php
session_start();
include("Conexion.php");

$id_venta = $_POST['id_venta'];
$motivo_rechazo = $_POST['motivo_rechazo'];

$sql = "
UPDATE ventas
SET estado = 'cancelada',
    motivo_rechazo = '$motivo_rechazo'
WHERE id_venta = $id_venta
";

if($conexion->query($sql)){

    header("Location: VentasPendientes.php");
    exit();

}else{

    echo "Error: " . $conexion->error;

}
?>