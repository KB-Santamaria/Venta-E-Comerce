<?php
session_start();
include("Conexion.php");

$id_carrito = $_GET['id'];

$conexion->query("
    DELETE FROM carrito
    WHERE id_carrito = $id_carrito
");

header("Location: Carrito.php");
exit();
?>