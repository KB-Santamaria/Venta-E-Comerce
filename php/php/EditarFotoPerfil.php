<?php
session_start();
include("Conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar foto de perfil</title>
    <link rel="stylesheet" href="../Inicio.css">
</head>
<body>

<div class="editar-perfil-container">

    <h1>Cambiar foto de perfil</h1>

    <form action="GuardarFotoPerfil.php" method="POST" enctype="multipart/form-data">

        <input type="file" name="foto_perfil" accept=".jpg,.jpeg,.png" required>

        <button type="submit" class="btn-guardar-perfil">
            Guardar foto
        </button>

    </form>

    <a href="Perfil.php" class="btn-volver-perfil">
        Volver al perfil
    </a>

</div>

</body>
</html>