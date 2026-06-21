<?php
session_start();
include("Conexion.php");

$id_usuario = $_GET['id'];

$sql = "
UPDATE bloqueos_comentarios
SET estado = 'inactivo'
WHERE id_usuario = $id_usuario
AND estado = 'activo'
";

if($conexion->query($sql)){
    header("Location: UsuariosBloqueados.php");
    exit();
}else{
    echo "Error al desbloquear comentarios: " . $conexion->error;
}
?>