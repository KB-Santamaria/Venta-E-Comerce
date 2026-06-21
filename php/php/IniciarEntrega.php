<?php
session_start();
include("Conexion.php");

$id_venta = $_GET['id'];

$sql = "
UPDATE ventas
SET
    estado = 'en_camino',
    fecha_en_camino = NOW()
WHERE id_venta = $id_venta
";

if($conexion->query($sql)){
    header("Location: PedidosAsignados.php");
    exit();
}else{
    echo "Error: " . $conexion->error;
}
?>