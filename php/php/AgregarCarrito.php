<?php
session_start();
include("Conexion.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../Loging.html");
    exit();
}

if(!isset($_GET['id'])){
    die("No se recibió el producto.");
}

$id_usuario = $_SESSION['id_usuario'];
$id_producto = $_GET['id'];

$consulta = $conexion->query("
    SELECT *
    FROM carrito
    WHERE id_usuario = $id_usuario
    AND id_producto = $id_producto
");

if($consulta->num_rows > 0){
    $conexion->query("
        UPDATE carrito
        SET cantidad = cantidad + 1
        WHERE id_usuario = $id_usuario
        AND id_producto = $id_producto
    ");
}else{
    $conexion->query("
        INSERT INTO carrito(id_usuario, id_producto, cantidad)
        VALUES($id_usuario, $id_producto, 1)
    ");
}

$volver = $_SERVER['HTTP_REFERER'] ?? '../Inicio.php';

header("Location: " . $volver);
exit();
?>