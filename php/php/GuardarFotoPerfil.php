<?php
session_start();
include("Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

if(!isset($_FILES['foto_perfil']) || $_FILES['foto_perfil']['error'] !== 0){
    die("Error: no se subió ninguna imagen.");
}

$nombreImagen = $_FILES['foto_perfil']['name'];
$tipoImagen = $_FILES['foto_perfil']['type'];
$tmpImagen = $_FILES['foto_perfil']['tmp_name'];
$tamanoImagen = $_FILES['foto_perfil']['size'];

$permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

if(!in_array($tipoImagen, $permitidos)){
    die("Formato no permitido. Solo JPG, JPEG y PNG.");
}

if($tamanoImagen > 2 * 1024 * 1024){
    die("La imagen es demasiado pesada. Máximo 2 MB.");
}

$extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
$nombreFinal = "perfil_" . $id_usuario . "." . strtolower($extension);

$carpetaDestino = "../ImgPerfiles/";
$rutaFinal = $carpetaDestino . $nombreFinal;

$consulta = $conexion->query("SELECT foto_perfil FROM usuarios WHERE id_usuario = $id_usuario");
$usuario = $consulta->fetch_assoc();

if(!empty($usuario['foto_perfil'])){
    $fotoAnterior = "../ImgPerfiles/" . $usuario['foto_perfil'];

    if(file_exists($fotoAnterior)){
        unlink($fotoAnterior);
    }
}

if(move_uploaded_file($tmpImagen, $rutaFinal)){

    $sql = "UPDATE usuarios SET foto_perfil = '$nombreFinal' WHERE id_usuario = $id_usuario";

    if($conexion->query($sql)){
        header("Location: Perfil.php");
        exit();
    }else{
        echo "Error al guardar foto: " . $conexion->error;
    }

}else{
    echo "Error al subir la imagen.";
}
?>