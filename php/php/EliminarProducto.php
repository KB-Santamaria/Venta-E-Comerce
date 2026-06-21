<?php
include("Conexion.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $consulta = $conexion->query("SELECT imagen FROM productos WHERE id_producto = $id");
    $producto = $consulta->fetch_assoc();

    if($producto && !empty($producto['imagen'])){
        $rutaImagen = "../ImgProductos/" . $producto['imagen'];

        if(file_exists($rutaImagen)){
            unlink($rutaImagen);
        }
    }

    $sql = "DELETE FROM productos WHERE id_producto = $id";

    if($conexion->query($sql)){
        header("Location: ../Inicio.php");
        exit();
    }else{
        echo "Error al eliminar: " . $conexion->error;
    }
}
?>