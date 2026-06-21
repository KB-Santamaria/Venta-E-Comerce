<?php
session_start();
include("Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$password_actual = $_POST['password_actual'];
$password_nueva = $_POST['password_nueva'];
$password_confirmar = $_POST['password_confirmar'];

$consulta = $conexion->query("
    SELECT contraseña
    FROM usuarios
    WHERE id_usuario = $id_usuario
");

$usuario = $consulta->fetch_assoc();

if($password_actual != $usuario['contraseña']){
    echo "
    <script>
        alert('La contraseña actual es incorrecta');
        window.location.href = 'CambiarPassword.php';
    </script>";
    exit();
}

if($password_nueva != $password_confirmar){
    echo "
    <script>
        alert('Las contraseñas nuevas no coinciden');
        window.location.href = 'CambiarPassword.php';
    </script>";
    exit();
}

$sql = "
    UPDATE usuarios
    SET contraseña = '$password_nueva'
    WHERE id_usuario = $id_usuario
";

if($conexion->query($sql)){
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Contraseña actualizada</title>
        <link rel='stylesheet' href='../Inicio.css'>
    </head>
    <body>

    <div class='modal-eliminar activo'>
        <div class='modal-contenido'>
            <h2>Contraseña actualizada</h2>
            <p>Tu contraseña fue actualizada correctamente.</p>

            <div class='modal-botones'>
                <a class='btn-confirmar-eliminar' href='Perfil.php'>
                    Entendido
                </a>
            </div>
        </div>
    </div>

    </body>
    </html>
    ";
}else{
    echo "Error al actualizar contraseña: " . $conexion->error;
}
?>