<?php
function tienePermiso($conexion, $id_rol, $permiso){
    $consulta = $conexion->query("
        SELECT p.nombre_permiso
        FROM rol_permisos rp
        INNER JOIN permisos p ON rp.id_permiso = p.id_permiso
        WHERE rp.id_rol = $id_rol
        AND p.nombre_permiso = '$permiso'
    ");

    return $consulta->num_rows > 0;
}
?>