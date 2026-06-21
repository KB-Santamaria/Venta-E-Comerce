<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['id_usuario'])){
    return;
}

include(__DIR__ . "/Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

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

if($suspension && $suspension->num_rows > 0){

    $datos = $suspension->fetch_assoc();

    $motivo = htmlspecialchars($datos['motivo']);
    $fecha_fin = htmlspecialchars($datos['fecha_fin']);

    session_destroy();

    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='stylesheet' href='/GolondrinaVarieties/Inicio.css'>
        <title>Cuenta suspendida</title>
    </head>
    <body>

    <div class='modal-eliminar activo'>
        <div class='modal-contenido'>

            <h2>⛔ Tu cuenta ha sido suspendida</h2>

            <p>
                Su cuenta ha sido suspendida temporalmente por un administrador.
            </p>

            <p>
                <strong>Motivo:</strong><br>
                $motivo
            </p>

            <p>
                <strong>Suspensión válida hasta:</strong><br>
                $fecha_fin
            </p>

            <div class='modal-botones'>
                <a href='/GolondrinaVarieties/Loging.html'
                   class='btn-confirmar-eliminar'>
                    Entendido
                </a>
            </div>

        </div>
    </div>

    </body>
    </html>
    ";

    exit();
}
?>