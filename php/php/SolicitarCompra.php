<?php
session_start();
include("Conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$usuario = $conexion->query("
    SELECT * FROM usuarios WHERE id_usuario = $id_usuario
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar compra</title>
    <link rel="stylesheet" href="../Carrito.css">
</head>
<body>

<div class="carrito-container">

    <h1>Solicitar compra</h1>

    <form action="GuardarVenta.php" method="POST" class="form-solicitud-compra">

        <input type="text" name="nombre_cliente" value="<?php echo $usuario['nombre_completo']; ?>" required>

        <input type="text" name="telefono" value="<?php echo $usuario['telefono']; ?>" placeholder="Número telefónico" required>

        <textarea name="direccion" placeholder="Dirección exacta" required></textarea>

        <select name="tipo_pago" required>
            <option value="">Tipo de pago</option>
            <option value="deposito">Depósito</option>
            <option value="efectivo">Efectivo</option>
        </select>

        <input type="number" step="0.01" name="monto_pago" placeholder="Si paga en efectivo, indique con cuánto paga">

        <button type="submit" class="btn-finalizar-compra">
            Enviar solicitud
        </button>

    </form>

    <div class="carrito-botones">
        <a href="Carrito.php" class="btn-seguir-comprando">Volver al carrito</a>
    </div>

</div>

</body>
</html>