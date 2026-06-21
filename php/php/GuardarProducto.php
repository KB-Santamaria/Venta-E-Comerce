<?php
include("Conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: AgregarProducto.php");
    exit();
}

$nombre = $_POST['nombre_producto'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$id_categoria = $_POST['id_categoria'];

if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== 0) {
    die("Error: no se subió ninguna imagen.");
}

$nombreImagen = $_FILES['imagen']['name'];
$tipoImagen = $_FILES['imagen']['type'];
$tmpImagen = $_FILES['imagen']['tmp_name'];

$permitidos = ['image/jpeg', 'image/png'];

if (!in_array($tipoImagen, $permitidos)) {
    die("Formato no permitido. Solo JPG, JPEG y PNG.");
}

$nombreFinal = time() . "_" . basename($nombreImagen);
$carpetaDestino = "../ImgProductos/";
$rutaFinal = $carpetaDestino . $nombreFinal;

if (move_uploaded_file($tmpImagen, $rutaFinal)) {

    $sql = "INSERT INTO productos(
        nombre_producto,
        descripcion,
        precio,
        stock,
        imagen,
        id_categoria
    ) VALUES (
        '$nombre',
        '$descripcion',
        '$precio',
        '$stock',
        '$nombreFinal',
        '$id_categoria'
    )";

    if ($conexion->query($sql)) {
        header("Location: AgregarProducto.php?exito=1");
        exit();
    } else {
        echo "Error al guardar producto: " . $conexion->error;
    }

} else {
    echo "Error al subir la imagen.";
}
?>