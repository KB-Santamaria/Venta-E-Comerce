<?php
include("Conexion.php");

$id_usuario = $_POST['id_usuario'];
$id_producto = $_POST['id_producto'];
$motivo = $_POST['motivo'];
$fecha_desbloqueo = $_POST['fecha_desbloqueo'];

$sql = "INSERT INTO bloqueos_comentarios(
    id_usuario,
    motivo,
    fecha_desbloqueo
) VALUES (
    '$id_usuario',
    '$motivo',
    '$fecha_desbloqueo'
)";

if($conexion->query($sql)){
    header("Location: DetalleProducto.php?id=" . $id_producto . "#comentarios");
    exit();
}else{
    echo "Error al bloquear usuario: " . $conexion->error;
}
?>