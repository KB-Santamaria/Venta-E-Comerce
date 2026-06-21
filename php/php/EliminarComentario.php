<?php
include("Conexion.php");

if(isset($_GET['id'])){

    $idComentario = $_GET['id'];

    $consulta = $conexion->query("
        SELECT id_producto
        FROM comentarios_producto
        WHERE id_comentario = $idComentario
    ");

    $comentario = $consulta->fetch_assoc();

    if($comentario){

        $idProducto = $comentario['id_producto'];

        $conexion->query("
            DELETE FROM comentarios_producto
            WHERE id_comentario = $idComentario
        ");

       header("Location: DetalleProducto.php?id=" . $idProducto . "#comentarios");
        exit();

    }else{
        echo "Comentario no encontrado.";
    }

}else{
    echo "ID no recibido.";
}
?>