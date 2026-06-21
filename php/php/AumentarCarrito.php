<?php
session_start();
include("Conexion.php");

$id_carrito = $_GET['id'];

$consulta = $conexion->query("
    SELECT c.cantidad, p.stock
    FROM carrito c
    INNER JOIN productos p ON c.id_producto = p.id_producto
    WHERE c.id_carrito = $id_carrito
");

$item = $consulta->fetch_assoc();

if($item['cantidad'] < $item['stock']){
    $conexion->query("
        UPDATE carrito
        SET cantidad = cantidad + 1
        WHERE id_carrito = $id_carrito
    ");
}

header("Location: Carrito.php");
exit();
?>