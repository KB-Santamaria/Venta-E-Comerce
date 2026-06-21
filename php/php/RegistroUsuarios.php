<?php
session_start();
include("Conexion.php");

$usuarios = $conexion->query("
    SELECT 
        u.*,
        r.nombre_rol,
        s.id_suspension,
        s.motivo,
        s.fecha_fin,
        s.estado AS estado_suspension
    FROM usuarios u
    INNER JOIN roles r ON u.id_rol = r.id_rol
    LEFT JOIN suspensiones_cuenta s 
        ON u.id_usuario = s.id_usuario
        AND s.estado = 'activa'
        AND s.fecha_fin > NOW()
    ORDER BY u.id_usuario DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuarios</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Registro de usuarios</h1>

    <?php while($usuario = $usuarios->fetch_assoc()){ ?>

        <div class="venta-card">

            <h3><?php echo $usuario['nombre_completo']; ?></h3>

            <p><strong>Correo:</strong> <?php echo $usuario['correo']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $usuario['telefono'] ?? 'No registrado'; ?></p>
            <p><strong>Rol:</strong> <?php echo $usuario['nombre_rol']; ?></p>

            <?php if(!empty($usuario['id_suspension'])){ ?>

                <p class="estado-suspendido">
                    🔴 Suspendido hasta: <?php echo $usuario['fecha_fin']; ?>
                </p>

                <p>
                    <strong>Motivo:</strong> <?php echo $usuario['motivo']; ?>
                </p>

            <?php } else { ?>

                <p class="estado-activo">
                    🟢 Cuenta activa
                </p>

            <?php } ?>

            <?php if($usuario['id_rol'] != 1){ ?>

                <?php if(!empty($usuario['id_suspension'])){ ?>

                    <a href="LevantarSuspension.php?id=<?php echo $usuario['id_usuario']; ?>"
                       class="btn-finalizar-compra">
                        Levantar suspensión
                    </a>

                <?php } else { ?>

                    <button type="button"
                            class="btn-rechazar-compra"
                            onclick="abrirModalSuspension(<?php echo $usuario['id_usuario']; ?>)">
                        Suspender cuenta
                    </button>

                <?php } ?>

            <?php } ?>

        </div>

    <?php } ?>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

<div class="modal-rechazo" id="modalSuspension">
    <div class="modal-rechazo-contenido">

        <h2>Suspender cuenta</h2>

        <form action="SuspenderUsuario.php" method="POST">

            <input type="hidden" name="id_usuario" id="idUsuarioSuspension">

            <textarea name="motivo"
                      placeholder="Motivo de la suspensión"
                      required></textarea>

            <label class="label-motorizado">Suspender hasta:</label>

            <input type="datetime-local"
                   name="fecha_fin"
                   required>

            <div class="modal-botones">
                <button type="button"
                        class="btn-cancelar"
                        onclick="cerrarModalSuspension()">
                    Cancelar
                </button>

                <button type="submit"
                        class="btn-confirmar-rechazo">
                    Confirmar suspensión
                </button>
            </div>

        </form>

    </div>
</div>

<script>
function abrirModalSuspension(idUsuario){
    document.getElementById("idUsuarioSuspension").value = idUsuario;
    document.getElementById("modalSuspension").classList.add("activo");
}

function cerrarModalSuspension(){
    document.getElementById("modalSuspension").classList.remove("activo");
}
</script>

</body>
</html>