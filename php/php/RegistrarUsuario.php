<?php
include("Conexion.php");

$nombre = $_POST['nombre_completo'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$contraseña = $_POST['contraseña'];
$confirmar = $_POST['confirmar_contraseña'];
$contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

$id_rol = 3;

if($contraseña != $confirmar){
    echo "
    <script>
        alert('Las contraseñas no coinciden');
        window.location.href = '../Loging.html';
    </script>";
    exit();
}

$verificar = $conexion->query("
    SELECT *
    FROM usuarios
    WHERE correo = '$correo'
");

if($verificar->num_rows > 0){
    echo "
    <script>
        alert('Este correo ya está registrado');
        window.location.href = '../Loging.html';
    </script>";
    exit();
}

$sql = "
INSERT INTO usuarios(
    nombre_completo,
    correo,
    contraseña,
    telefono,
    id_rol
) VALUES (
    '$nombre',
    '$correo',
    '$contraseñaHash',
    '$telefono',
    '$id_rol'
)
";

if($conexion->query($sql)){

    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../Loging.css'>
        <title>Cuenta creada</title>
    </head>
    <body>

        <div class='modal-login-error activo'>
            <div class='modal-login-contenido exito'>

                <h2>🎉 Cuenta creada</h2>

                <p>
                    Su cuenta fue registrada correctamente.
                    Ahora puede iniciar sesión.
                </p>

                <a href='../Loging.html'
                   class='btn-volver-login'>
                    Ir al Login
                </a>

            </div>
        </div>

    </body>
    </html>
    ";

}else{

    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../Loging.css'>
        <title>Error</title>
    </head>
    <body>

        <div class='modal-login-error activo'>
            <div class='modal-login-contenido'>

                <h2>❌ Error</h2>

                <p>
                    Error al registrar usuario:
                    ".$conexion->error."
                </p>

                <a href='../Loging.html'
                   class='btn-volver-login'>
                    Volver
                </a>

            </div>
        </div>

    </body>
    </html>
    ";

}
?>