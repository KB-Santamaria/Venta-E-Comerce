<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../Loging.html");
    exit();
}

$id = $_GET['id'];

$consulta = $conexion->query("
SELECT p.*, c.nombre_categoria
FROM productos p
INNER JOIN categorias c ON p.id_categoria = c.id_categoria
WHERE p.id_producto = $id
");

$producto = $consulta->fetch_assoc();

$comentarios = $conexion->query("
SELECT c.*, u.nombre_completo, u.foto_perfil
FROM comentarios_producto c
INNER JOIN usuarios u
ON c.id_usuario = u.id_usuario
WHERE c.id_producto = $id
ORDER BY c.fecha_comentario DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del producto</title>
    <link rel="stylesheet" href="../Inicio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="detalle-producto">

        <div class="detalle-imagen">
            <img src="../ImgProductos/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre_producto']; ?>">
        </div>

        <div class="detalle-info">
            <h1><?php echo $producto['nombre_producto']; ?></h1>

            <p><strong>Categoría:</strong> <?php echo $producto['nombre_categoria']; ?></p>
            <p><strong>Unidades disponibles:</strong> <?php echo $producto['stock']; ?></p>

            <h2>C$<?php echo number_format($producto['precio'], 2); ?></h2>

            <h3>Descripción</h3>
            <p><?php echo $producto['descripcion']; ?></p>

            <button class="btn-carrito-producto">
                <i class="fa-solid fa-cart-plus"></i>
                <span>Añadir al carrito</span>
            </button>

            <a class="btn-volver-detalle" href="../Inicio.php">Volver</a>
        </div>

    </div>

    <div class="comentarios-producto" id="comentarios">

        <h2>Comentarios y calificación</h2>

        <form class="form-comentario" action="GuardarComentario.php" method="POST">

            <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
            <textarea name="comentario" placeholder="Escribe tu comentario" required></textarea>

            <select name="calificacion" required>
                <option value="">Calificación</option>
                <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                <option value="4">⭐⭐⭐⭐ Muy bueno</option>
                <option value="3">⭐⭐⭐ Bueno</option>
                <option value="2">⭐⭐ Regular</option>
                <option value="1">⭐ Malo</option>
            </select>

            <button type="submit">Enviar comentario</button>

        </form>

        <div class="lista-comentarios">

            <?php while($comentario = $comentarios->fetch_assoc()){ ?>

                <div class="comentario-card">

    <div class="comentario-card">

    <div class="comentario-header">

        <?php if(!empty($comentario['foto_perfil'])){ ?>

    <img class="avatar-comentario-img"
         src="../ImgPerfiles/<?php echo $comentario['foto_perfil']; ?>">

<?php } else { ?>

    <div class="avatar-comentario">
        <?php echo strtoupper(substr($comentario['nombre_completo'], 0, 1)); ?>
    </div>

<?php } ?>

        <div class="comentario-info">

            <h4><?php echo $comentario['nombre_completo']; ?></h4>

            <div class="estrellas">
                <?php echo str_repeat("⭐", $comentario['calificacion']); ?>
            </div>

            <p class="texto-comentario">
                <?php echo $comentario['comentario']; ?>
            </p>

            <div class="acciones-comentario">

                <a class="btn-eliminar-comentario"
                   href="EliminarComentario.php?id=<?php echo $comentario['id_comentario']; ?>">
                    <i class="fa-solid fa-trash"></i>
                </a>

                <a class="btn-bloquear-comentario"
                   href="BloquearComentario.php?id_usuario=<?php echo $comentario['id_usuario']; ?>&id_producto=<?php echo $id; ?>">
                    <i class="fa-solid fa-ban"></i>
                </a>

            </div>

            <small class="fecha-comentario">
                <?php echo $comentario['fecha_comentario']; ?>
            </small>

        </div>

    </div>

</div>

            <?php } ?>

        </div>

    </div>

</body>

</html>