<?php
session_start();
include("Conexion.php");

$id_usuario = $_GET['id'];

$sql = "
UPDATE suspensiones_cuenta
SET estado = 'finalizada'
WHERE id_usuario = $id_usuario
AND estado = 'activa'
";

if($conexion->query($sql)){
    header("Location: RegistroUsuarios.php");
    exit();
}else{
    echo "Error al levantar suspensión: " . $conexion->error;
}
?>