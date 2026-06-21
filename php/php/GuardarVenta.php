<?php
session_start();
include("Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$nombre_cliente = $_POST['nombre_cliente'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$tipo_pago = $_POST['tipo_pago'];

$monto_pago = "NULL";
$vuelto = "NULL";
$comprobante_pago = "NULL";

$carrito = $conexion->query("
    SELECT c.*, p.precio
    FROM carrito c
    INNER JOIN productos p ON c.id_producto = p.id_producto
    WHERE c.id_usuario = $id_usuario
");

$total = 0;
$items = [];

while($item = $carrito->fetch_assoc()){
    $subtotal = $item['precio'] * $item['cantidad'];
    $total += $subtotal;
    $items[] = $item;
}

if(count($items) == 0){
    die("El carrito está vacío.");
}

if($tipo_pago == "efectivo"){
    $monto_pago = $_POST['monto_pago'];
    $vuelto = $monto_pago - $total;
}

if($tipo_pago == "deposito" && isset($_FILES['comprobante_pago']) && $_FILES['comprobante_pago']['error'] == 0){

    $nombreImagen = $_FILES['comprobante_pago']['name'];
    $tmpImagen = $_FILES['comprobante_pago']['tmp_name'];
    $tipoImagen = $_FILES['comprobante_pago']['type'];

    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];

    if(!in_array($tipoImagen, $permitidos)){
        die("Formato no permitido. Solo JPG, PNG o WEBP.");
    }

    $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
    $nombreFinal = "comprobante_" . time() . "." . strtolower($extension);

    $rutaDestino = "../ImgComprobantes/" . $nombreFinal;

    if(move_uploaded_file($tmpImagen, $rutaDestino)){
        $comprobante_pago = "'" . $nombreFinal . "'";
    }else{
        die("Error al subir el comprobante.");
    }
}

$sqlVenta = "
INSERT INTO ventas(
    id_usuario,
    nombre_cliente,
    telefono,
    direccion,
    tipo_pago,
    monto_pago,
    vuelto,
    comprobante_pago,
    total,
    aplica_descuento,
    descuento,
    concepto_descuento,
    total_final,
    estado
) VALUES (
    '$id_usuario',
    '$nombre_cliente',
    '$telefono',
    '$direccion',
    '$tipo_pago',
    $monto_pago,
    $vuelto,
    $comprobante_pago,
    '$total',
    'no',
    0,
    NULL,
    '$total',
    'pendiente'
)
";

if($conexion->query($sqlVenta)){

    $id_venta = $conexion->insert_id;

    foreach($items as $item){

        $subtotal = $item['precio'] * $item['cantidad'];

        $conexion->query("
            INSERT INTO detalle_venta(
                id_venta,
                id_producto,
                cantidad,
                precio_unitario,
                subtotal
            ) VALUES (
                '$id_venta',
                '".$item['id_producto']."',
                '".$item['cantidad']."',
                '".$item['precio']."',
                '$subtotal'
            )
        ");
    }

    $conexion->query("
        DELETE FROM carrito
        WHERE id_usuario = $id_usuario
    ");

    header("Location: Carrito.php?solicitud=ok");
    exit();

}else{
    echo "Error al guardar venta: " . $conexion->error;
}


?>

