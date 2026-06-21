<?php
session_start();
include("Conexion.php");

$id_carrito = $_GET['id'];

$consulta = $conexion->query("
    SELECT cantidad
    FROM carrito
    WHERE id_carrito = $id_carrito
");

$item = $consulta->fetch_assoc();

if($item['cantidad'] > 1){

    $conexion->query("
        UPDATE carrito
        SET cantidad = cantidad - 1
        WHERE id_carrito = $id_carrito
    ");

}else{

    $conexion->query("
        DELETE FROM carrito
        WHERE id_carrito = $id_carrito
    ");
}

header("Location: Carrito.php");
exit();
?>