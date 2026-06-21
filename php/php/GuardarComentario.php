<?php
session_start();
include("Conexion.php");

$id_producto = $_POST['id_producto'];
$id_usuario = $_SESSION['id_usuario'];
$comentario = $_POST['comentario'];
$calificacion = $_POST['calificacion'];

/* Verificar si el usuario está bloqueado para comentar */
$bloqueo = $conexion->query("
    SELECT *
    FROM bloqueos_comentarios
    WHERE id_usuario = $id_usuario
    AND estado = 'activo'
    AND fecha_desbloqueo > NOW()
");

if($bloqueo->num_rows > 0){

    $datosBloqueo = $bloqueo->fetch_assoc();

    $motivo = $datosBloqueo['motivo'];
$desbloqueo = $datosBloqueo['fecha_desbloqueo'];

echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Comentario bloqueado</title>
    <link rel='stylesheet' href='../Inicio.css'>
</head>
<body>

<div class='modal-eliminar activo'>

    <div class='modal-contenido'>

        <h2>⚠ Comentario bloqueado</h2>

        <p>
            No puedes comentar temporalmente.
        </p>

        <p>
            <strong>Motivo:</strong> $motivo
        </p>

        <p>
            <strong>Desbloqueo:</strong> $desbloqueo
        </p>

        <div class='modal-botones'>
            <a class='btn-confirmar-eliminar'
               href='DetalleProducto.php?id=$id_producto#comentarios'>
                Entendido
            </a>
        </div>

    </div>

</div>

</body>
</html>
";

exit();

    exit();
}

/* Si no está bloqueado, guarda el comentario */
$sql = "INSERT INTO comentarios_producto(
    id_producto,
    id_usuario,
    comentario,
    calificacion
) VALUES (
    '$id_producto',
    '$id_usuario',
    '$comentario',
    '$calificacion'
)";

if($conexion->query($sql)){
    header("Location: DetalleProducto.php?id=" . $id_producto . "#comentarios");
    exit();
}else{
    echo "Error al guardar comentario: " . $conexion->error;
}
?>