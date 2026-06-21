<?php
session_start();
include("Conexion.php");

$correo = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

$sql = "
SELECT *
FROM usuarios
WHERE correo = '$correo'
";

$resultado = $conexion->query($sql);

if($resultado->num_rows > 0){

    $usuario = $resultado->fetch_assoc();
    $id_usuario = $usuario['id_usuario'];

    $passwordCorrecta = password_verify($contraseña, $usuario['contraseña']);

    // Compatibilidad temporal con contraseñas antiguas en texto plano
    if(!$passwordCorrecta && $contraseña == $usuario['contraseña']){
        $passwordCorrecta = true;

        $nuevoHash = password_hash($contraseña, PASSWORD_DEFAULT);

        $conexion->query("
            UPDATE usuarios
            SET contraseña = '$nuevoHash'
            WHERE id_usuario = $id_usuario
        ");
    }

    if($passwordCorrecta){

        $conexion->query("
            UPDATE suspensiones_cuenta
            SET estado = 'finalizada'
            WHERE id_usuario = $id_usuario
            AND estado = 'activa'
            AND fecha_fin <= NOW()
        ");

        $suspension = $conexion->query("
            SELECT *
            FROM suspensiones_cuenta
            WHERE id_usuario = $id_usuario
            AND estado = 'activa'
            AND fecha_fin > NOW()
        ");

        if($suspension->num_rows > 0){

            $datosSuspension = $suspension->fetch_assoc();

            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <link rel='stylesheet' href='../Loging.css'>
                <title>Cuenta suspendida</title>
            </head>
            <body>

            <div class='modal-login-error activo'>
                <div class='modal-login-contenido'>
                    <h2>Cuenta suspendida</h2>

                    <p>No puede iniciar sesión hasta:</p>
                    <p><strong>".$datosSuspension['fecha_fin']."</strong></p>

                    <p>Motivo: ".$datosSuspension['motivo']."</p>

                    <a href='../Loging.html' class='btn-volver-login'>
                        Volver
                    </a>
                </div>
            </div>

            </body>
            </html>
            ";

            exit();
        }

        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre_completo'];
        $_SESSION['id_rol'] = $usuario['id_rol'];

        header("Location: ../Inicio.php");
        exit();

    }else{

        mostrarErrorLogin();

    }

}else{

    mostrarErrorLogin();

}

function mostrarErrorLogin(){
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='../Loging.css'>
        <title>Error de inicio</title>
    </head>
    <body>

    <div class='modal-login-error activo'>
        <div class='modal-login-contenido'>
            <h2>Error de inicio de sesión</h2>
            <p>Correo o contraseña incorrectos.</p>

            <a href='../Loging.html' class='btn-volver-login'>
                Intentar nuevamente
            </a>
        </div>
    </div>

    </body>
    </html>
    ";
    exit();
}
?>