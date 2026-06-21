<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");

$roles = $conexion->query("
    SELECT *
    FROM roles
    ORDER BY nombre_rol ASC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Crear usuario</h1>

    <form action="GuardarUsuarioAdmin.php" method="POST" class="venta-card">

        <label>Nombre completo</label>
        <input type="text" name="nombre_completo" required>

        <label>Teléfono</label>
        <input type="text" name="telefono" required>

        <label>Correo</label>
        <input type="email" name="correo" required>

        <label>Contraseña</label>
        <input type="password" name="contraseña" required>

        <label>Rol</label>
        <select name="id_rol" required>
            <option value="">Seleccione un rol</option>

            <?php while($rol = $roles->fetch_assoc()){ ?>
                <option value="<?php echo $rol['id_rol']; ?>">
                    <?php echo $rol['nombre_rol']; ?>
                </option>
            <?php } ?>
        </select>

        <br><br>

        <button type="submit" class="btn-finalizar-compra">
            Crear usuario
        </button>

    </form>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

</body>
</html>