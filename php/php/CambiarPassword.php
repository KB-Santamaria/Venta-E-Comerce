<?php
session_start();
include("Conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña</title>
    <link rel="stylesheet" href="../Inicio.css">
</head>

<body>

<div class="editar-perfil-container">

    <h1>Cambiar contraseña</h1>

    <form action="GuardarPassword.php" method="POST">

        <input type="password" name="password_actual" placeholder="Contraseña actual" required>

        <input type="password" name="password_nueva" placeholder="Nueva contraseña" required>

        <input type="password" name="password_confirmar" placeholder="Confirmar nueva contraseña" required>

        <button type="submit" class="btn-guardar-perfil">
            Guardar cambios
        </button>

    </form>

    <a href="Perfil.php" class="btn-volver-perfil">
        Volver al perfil
    </a>

</div>

</body>
</html>