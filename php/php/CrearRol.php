<?php
session_start();
include("Conexion.php");
include("VerificarSuspension.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear rol</title>
    <link rel="stylesheet" href="../Ventas.css">
</head>
<body>

<div class="ventas-pendientes-container">

    <h1>Crear nuevo rol</h1>

    <form action="GuardarRol.php" method="POST" class="venta-card">

        <label>Nombre del rol</label>
        <input type="text" name="nombre_rol" required>

        <label>Descripción</label>
        <textarea name="descripcion" required></textarea>

        <h3>Categorías</h3>
        <label><input type="checkbox" name="permisos[]" value="menu_categorias"> Activar menú Categorías</label>
        <label><input type="checkbox" name="permisos[]" value="ver_camisas"> Camisas</label>
        <label><input type="checkbox" name="permisos[]" value="ver_pantalones"> Pantalones</label>
        <label><input type="checkbox" name="permisos[]" value="ver_joyeria"> Joyería</label>
        <label><input type="checkbox" name="permisos[]" value="ver_bolsos"> Bolsos</label>
        <label><input type="checkbox" name="permisos[]" value="ver_calzado"> Calzado</label>

        <h3>Administración</h3>
        <label><input type="checkbox" name="permisos[]" value="menu_administracion"> Activar menú Administración</label>
        <label><input type="checkbox" name="permisos[]" value="agregar_producto"> Agregar producto</label>
        <label><input type="checkbox" name="permisos[]" value="editar_producto"> Editar producto</label>
        <label><input type="checkbox" name="permisos[]" value="eliminar_producto"> Eliminar producto</label>
        <label><input type="checkbox" name="permisos[]" value="ventas_pendientes"> Ventas pendientes</label>
        <label><input type="checkbox" name="permisos[]" value="registro_usuarios"> Registro usuarios</label>
        <label><input type="checkbox" name="permisos[]" value="usuarios_bloqueados"> Usuarios bloqueados</label>
        <label><input type="checkbox" name="permisos[]" value="crear_roles"> Crear roles</label>

        <h3>Entregas</h3>
        <label><input type="checkbox" name="permisos[]" value="menu_entregas"> Activar menú Entregas</label>
        <label><input type="checkbox" name="permisos[]" value="pedidos_asignados"> Pedidos asignados</label>
        <label><input type="checkbox" name="permisos[]" value="historial_entregas"> Historial entregas</label>

        <h3>Mis movimientos</h3>
        <label><input type="checkbox" name="permisos[]" value="menu_movimientos"> Activar menú Mis movimientos</label>
        <label><input type="checkbox" name="permisos[]" value="historial_compras"> Historial compras</label>
        <label><input type="checkbox" name="permisos[]" value="monitoreo_compra"> Monitoreo compra</label>
        <label><input type="checkbox" name="permisos[]" value="historial_penalizaciones"> Historial penalizaciones</label>

        <br><br>

        <button type="submit" class="btn-finalizar-compra">
            Crear Nuevo Rol
        </button>

    </form>

    <div class="carrito-botones">
        <a href="../Inicio.php" class="btn-seguir-comprando">Volver</a>
    </div>

</div>

</body>
</html>