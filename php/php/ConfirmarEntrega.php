<?php
session_start();
include("Conexion.php");

$id_venta = $_POST['id_venta'];
$observacion = $_POST['observacion_entrega'];

if(empty($_FILES['foto_entrega']['name'])){
    die("Debe adjuntar una foto de evidencia.");
}

$nombreFoto = time() . "_" . $_FILES['foto_entrega']['name'];

$rutaTemporal = $_FILES['foto_entrega']['tmp_name'];
$rutaDestino = "../ImgEntregas/" . $nombreFoto;

move_uploaded_file($rutaTemporal, $rutaDestino);

$sql = "
UPDATE ventas
SET
    estado = 'entregada',
    foto_entrega = '$nombreFoto',
    observacion_entrega = '$observacion',
    fecha_entrega = NOW()
WHERE id_venta = $id_venta
";

if($conexion->query($sql)){
    header("Location: PedidosAsignados.php");
    exit();
}else{
    echo "Error: " . $conexion->error;
}
?>