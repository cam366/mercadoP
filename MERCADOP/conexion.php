<?php
$conexion = new mysqli("sql312.infinityfree.com", "if0_39426784", "locuritas3000", "if0_39426784_tienda");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
