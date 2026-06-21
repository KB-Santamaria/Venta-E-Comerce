<?php
session_start();
include("Conexion.php");

$id_usuario = $_POST['id_usuario'];
$motivo = $_POST['motivo'];
$fecha_fin = $_POST['fecha_fin'];

$sql = "
INSERT INTO suspensiones_cuenta(
    id_usuario,
    motivo,
    fecha_fin,
    estado
) VALUES (
    '$id_usuario',
    '$motivo',
    '$fecha_fin',
    'activa'
)
";

if($conexion->query($sql)){
    header("Location: RegistroUsuarios.php");
    exit();
}else{
    echo "Error al suspender usuario: " . $conexion->error;
}
?>