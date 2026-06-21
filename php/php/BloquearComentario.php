<?php
include("Conexion.php");

$id_usuario = $_GET['id_usuario'];
$id_producto = $_GET['id_producto'];

$usuario = $conexion->query("
    SELECT * FROM usuarios WHERE id_usuario = $id_usuario
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bloquear usuario</title>
    <link rel="stylesheet" href="../Inicio.css">
</head>

<body>

<div class="bloqueo-container">

    <h1>Bloquear usuario</h1>

    <p>
        Usuario:
        <strong><?php echo $usuario['nombre_completo']; ?></strong>
    </p>

    <form action="GuardarBloqueoComentario.php" method="POST">

        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">

        <label>Motivo del bloqueo</label>
        <textarea name="motivo" required placeholder="Ejemplo: Comentarios ofensivos, spam, lenguaje inapropiado..."></textarea>

        <label>Fecha y hora de desbloqueo</label>
        <input type="datetime-local" name="fecha_desbloqueo" required>

        <button type="submit">Bloquear usuario</button>

        <a href="DetalleProducto.php?id=<?php echo $id_producto; ?>#comentarios">
            Cancelar
        </a>

    </form>

</div>

</body>
</html>