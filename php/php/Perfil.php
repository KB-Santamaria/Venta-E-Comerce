<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../Loging.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$consulta = $conexion->query("
SELECT u.*, r.nombre_rol
FROM usuarios u
INNER JOIN roles r ON u.id_rol = r.id_rol
WHERE u.id_usuario = $id_usuario
");

$usuario = $consulta->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="../Inicio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="perfil-container">
    <a href="../Inicio.php" class="btn-atras-perfil">
    ← Atrás
</a>
    </a>

    <div class="perfil-avatar-wrapper">

        <?php if(!empty($usuario['foto_perfil'])){ ?>

            <img class="perfil-foto-img" src="../ImgPerfiles/<?php echo $usuario['foto_perfil']; ?>">

        <?php } else { ?>

            <div class="avatar-perfil">
                <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
            </div>

        <?php } ?>

        <a href="EditarFotoPerfil.php" class="btn-editar-foto">
            <i class="fa-solid fa-pen"></i>
        </a>

    </div>

    <h1><?php echo $usuario['nombre_completo']; ?></h1>

    <div class="perfil-datos">

        <p>
            <strong>Correo:</strong>
            <?php echo $usuario['correo']; ?>
        </p>

        <p>
            <strong>Número telefónico:</strong>
            <?php echo !empty($usuario['telefono']) ? $usuario['telefono'] : 'No registrado'; ?>
        </p>

        <p>
            <strong>Rol:</strong>
            <?php echo $usuario['nombre_rol']; ?>
        </p>

    </div>

    <div class="perfil-botones">
        <a href="EditarPerfil.php">Editar información</a>
        <a href="CambiarPassword.php">Cambiar contraseña</a>
        <a href="CerrarSesion.php" class="btn-cerrar-sesion">Cerrar sesión</a>
    </div>

</div>

</body>
</html>