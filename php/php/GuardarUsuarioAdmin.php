<?php
session_start();
include("Conexion.php");

$nombre = $_POST['nombre_completo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$contraseña = $_POST['contraseña'];
$contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);
$id_rol = $_POST['id_rol'];

function mostrarModal($titulo, $mensaje, $link, $textoBoton){
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>$titulo</title>
        <link rel='stylesheet' href='../Inicio.css'>
    </head>
    <body>

        <div class='modal-eliminar activo'>
            <div class='modal-contenido'>

                <h2>$titulo</h2>
                <p>$mensaje</p>

                <div class='modal-botones'>
                    <a href='$link' class='btn-confirmar-eliminar'>
                        $textoBoton
                    </a>
                </div>

            </div>
        </div>

    </body>
    </html>
    ";
    exit();
}

$verificarCorreo = $conexion->query("
    SELECT id_usuario
    FROM usuarios
    WHERE correo = '$correo'
");

if($verificarCorreo->num_rows > 0){
    mostrarModal(
        "Correo existente",
        "Ya existe un usuario registrado con ese correo.",
        "CrearUsuario.php",
        "Volver"
    );
}

$sql = "
INSERT INTO usuarios(
    nombre_completo,
    correo,
    contraseña,
    id_rol,
    telefono
) VALUES (
    '$nombre',
    '$correo',
    '$contraseñaHash',
    '$id_rol',
    '$telefono'
)
";

if($conexion->query($sql)){
    mostrarModal(
        "Usuario creado",
        "El usuario fue creado correctamente.",
        "CrearUsuario.php",
        "Aceptar"
    );
}else{
    mostrarModal(
        "Error",
        "Error al crear usuario: " . $conexion->error,
        "CrearUsuario.php",
        "Volver"
    );
}
?>