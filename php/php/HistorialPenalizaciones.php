<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../Loging.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$suspensiones = $conexion->query("
    SELECT *
    FROM suspensiones_cuenta
    WHERE id_usuario = $id_usuario
    ORDER BY fecha_inicio DESC
");

$bloqueos = $conexion->query("
    SELECT *
    FROM bloqueos_comentarios
    WHERE id_usuario = $id_usuario
    ORDER BY fecha_bloqueo DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de penalizaciones</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Historial de penalizaciones</h1>

    <?php if($suspensiones->num_rows == 0 && $bloqueos->num_rows == 0){ ?>

        <div class="venta-card">
            <p>No tienes penalizaciones registradas.</p>
        </div>

    <?php } ?>

    <?php while($suspension = $suspensiones->fetch_assoc()){ ?>

        <div class="venta-card">

            <h3>⛔ Suspensión de cuenta</h3>

            <p><strong>Motivo:</strong> <?php echo $suspension['motivo']; ?></p>
            <p><strong>Fecha inicio:</strong> <?php echo $suspension['fecha_inicio']; ?></p>
            <p><strong>Fecha fin:</strong> <?php echo $suspension['fecha_fin']; ?></p>
            <p><strong>Estado:</strong> <?php echo $suspension['estado']; ?></p>

        </div>

    <?php } ?>

    <?php while($bloqueo = $bloqueos->fetch_assoc()){ ?>

        <div class="venta-card">

            <h3>💬 Bloqueo de comentarios</h3>

            <p><strong>Motivo:</strong> <?php echo $bloqueo['motivo']; ?></p>
            <p><strong>Fecha bloqueo:</strong> <?php echo $bloqueo['fecha_bloqueo']; ?></p>
            <p><strong>Fecha desbloqueo:</strong> <?php echo $bloqueo['fecha_desbloqueo']; ?></p>
            <p><strong>Estado:</strong> <?php echo $bloqueo['estado']; ?></p>

        </div>

    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

</body>
</html>