<?php
session_start();
include("Conexion.php");

$nombre_rol = $_POST['nombre_rol'];
$descripcion = $_POST['descripcion'];
$permisos = $_POST['permisos'] ?? [];

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

/* Verificar si el rol ya existe */
$verificarRol = $conexion->query("
    SELECT *
    FROM roles
    WHERE nombre_rol = '$nombre_rol'
");

if($verificarRol->num_rows > 0){
    mostrarModal(
        "Rol existente",
        "Ya existe un rol con ese nombre.",
        "CrearRol.php",
        "Volver"
    );
}

/* Crear rol */
$sqlRol = "
INSERT INTO roles(nombre_rol, descripcion)
VALUES('$nombre_rol', '$descripcion')
";

if($conexion->query($sqlRol)){

    $id_rol = $conexion->insert_id;

    foreach($permisos as $permiso){

        $buscarPermiso = $conexion->query("
            SELECT id_permiso
            FROM permisos
            WHERE nombre_permiso = '$permiso'
        ");

        if($buscarPermiso->num_rows > 0){

            $datoPermiso = $buscarPermiso->fetch_assoc();
            $id_permiso = $datoPermiso['id_permiso'];

            $conexion->query("
                INSERT INTO rol_permisos(id_rol, id_permiso)
                VALUES('$id_rol', '$id_permiso')
            ");
        }
    }

    mostrarModal(
        "Rol creado",
        "El rol fue creado correctamente.",
        "CrearRol.php",
        "Aceptar"
    );

}else{

    mostrarModal(
        "Error",
        "Error al crear rol: " . $conexion->error,
        "CrearRol.php",
        "Volver"
    );
}
?>