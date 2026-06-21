<?php
session_start();
include("Conexion.php");

$suspendidos = $conexion->query("
    SELECT
        u.id_usuario,
        u.nombre_completo,
        u.correo,
        s.motivo,
        s.fecha_inicio,
        s.fecha_fin
    FROM usuarios u
    INNER JOIN suspensiones_cuenta s
        ON u.id_usuario = s.id_usuario
    WHERE s.estado = 'activa'
    AND s.fecha_fin > NOW()
    ORDER BY s.fecha_inicio DESC
");

$bloqueados = $conexion->query("
    SELECT
        u.id_usuario,
        u.nombre_completo,
        u.correo,
        b.motivo,
        b.fecha_bloqueo,
        b.fecha_desbloqueo
    FROM usuarios u
    INNER JOIN bloqueos_comentarios b
        ON u.id_usuario = b.id_usuario
    WHERE b.estado = 'activo'
    AND b.fecha_desbloqueo > NOW()
    ORDER BY b.fecha_bloqueo DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios bloqueados / baneados</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Usuarios Bloqueados / Baneados</h1>

    <h2 class="titulo-seccion">⛔ Suspensiones activas</h2>

    <?php if($suspendidos->num_rows == 0){ ?>
        <div class="venta-card">
            <p>No hay usuarios suspendidos actualmente.</p>
        </div>
    <?php } ?>

    <?php while($usuario = $suspendidos->fetch_assoc()){ ?>
        <div class="venta-card">

            <h3><?php echo $usuario['nombre_completo']; ?></h3>

            <p><strong>Correo:</strong> <?php echo $usuario['correo']; ?></p>
            <p><strong>Motivo:</strong> <?php echo $usuario['motivo']; ?></p>
            <p><strong>Fecha inicio:</strong> <?php echo $usuario['fecha_inicio']; ?></p>
            <p><strong>Suspendido hasta:</strong> <?php echo $usuario['fecha_fin']; ?></p>

            <a href="LevantarSuspension.php?id=<?php echo $usuario['id_usuario']; ?>"
               class="btn-finalizar-compra">
                Levantar suspensión
            </a>

        </div>
    <?php } ?>

    <h2 class="titulo-seccion">💬 Bloqueos de comentarios activos</h2>

    <?php if($bloqueados->num_rows == 0){ ?>
        <div class="venta-card">
            <p>No hay usuarios bloqueados para comentar actualmente.</p>
        </div>
    <?php } ?>

    <?php while($usuario = $bloqueados->fetch_assoc()){ ?>
        <div class="venta-card">

            <h3><?php echo $usuario['nombre_completo']; ?></h3>

            <p><strong>Correo:</strong> <?php echo $usuario['correo']; ?></p>
            <p><strong>Motivo:</strong> <?php echo $usuario['motivo']; ?></p>
            <p><strong>Fecha bloqueo:</strong> <?php echo $usuario['fecha_bloqueo']; ?></p>
            <p><strong>Fecha desbloqueo:</strong> <?php echo $usuario['fecha_desbloqueo']; ?></p>

            <a href="DesbloquearComentario.php?id=<?php echo $usuario['id_usuario']; ?>"
               class="btn-finalizar-compra">
                Desbloquear comentarios
            </a>

        </div>
    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">
            Volver
        </a>
    </div>

</div>

</body>
</html>