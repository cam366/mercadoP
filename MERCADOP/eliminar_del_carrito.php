<?php
session_start();

$id = $_POST['id'] ?? null;

if ($id !== null && isset($_SESSION['carrito'])) {
    // Elimina solo la primera ocurrencia del producto
    $index = array_search($id, $_SESSION['carrito']);
    if ($index !== false) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
    }
}

header("Location: carrito.php");
