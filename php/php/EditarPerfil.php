<?php
session_start();
include("Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$consulta = $conexion->query("
SELECT *
FROM usuarios
WHERE id_usuario = $id_usuario
");

$usuario = $consulta->fetch_assoc();

if(isset($_POST['guardar'])){

    $nombre = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE usuarios SET
            nombre_completo = '$nombre',
            correo = '$correo',
            telefono = '$telefono'
            WHERE id_usuario = $id_usuario";

    if($conexion->query($sql)){
        $_SESSION['nombre'] = $nombre;
        header("Location: Perfil.php");
        exit();
    }else{
        echo "Error al actualizar perfil: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar perfil</title>
    <link rel="stylesheet" href="../Inicio.css">
</head>

<body>

<<div class="editar-perfil-container">

    <h1>Editar perfil</h1>

    <form method="POST">

        <input type="text"
               name="nombre_completo"
               value="<?php echo $usuario['nombre_completo']; ?>">

        <input type="email"
               name="correo"
               value="<?php echo $usuario['correo']; ?>">

        <input type="text"
               name="telefono"
               value="<?php echo $usuario['telefono']; ?>">

        <button type="submit"
                name="guardar"
                class="btn-guardar-perfil">
            Guardar cambios
        </button>

    </form>

    <a href="Perfil.php" class="btn-volver-perfil">
        Volver al perfil
    </a>

</div>

</body>
</html>