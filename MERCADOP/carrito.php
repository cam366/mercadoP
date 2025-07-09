<?php
session_start();
include 'conexion.php';

$carrito = $_SESSION['carrito'] ?? [];
$productos = [];
$total = 0;

if ($carrito) {
    $ids = implode(',', array_map('intval', $carrito)); // Seguridad
    $sql = "SELECT * FROM productos WHERE id IN ($ids)";
    $result = $conexion->query($sql);

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
        $total += $row['precio'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito de Compras</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<h1>🛒 Carrito</h1>

<?php if ($productos): ?>
  <?php foreach($productos as $prod): ?>
    <div class="producto">
      <strong><?php echo htmlspecialchars($prod['nombre']); ?></strong> - $<?php echo number_format($prod['precio'], 0, ',', '.'); ?>
      <form method="POST" action="eliminar_del_carrito.php" style="display:inline;">
        <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
        <button type="submit" style="background:red; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; margin-left:10px;">
          Eliminar
        </button>
      </form>
    </div>
  <?php endforeach; ?>

  <div class="total">Total: $<?php echo number_format($total, 0, ',', '.'); ?></div>

  <!-- Formulario de compra -->
  <div class="formulario">
    <h2>Finalizar compra</h2>
    <form method="POST" action="finalizar_compra.php">
      <label>Nombre completo:
        <input type="text" name="nombre" required>
      </label>
      <label>Email:
        <input type="email" name="email" required>
      </label>
      <label>Dirección:
        <input type="text" name="direccion" required>
      </label>
      <button type="submit">Confirmar pedido</button>
    </form>
  </div>
<?php else: ?>
  <p>Tu carrito está vacío.</p>
<?php endif; ?>

<a href="index.php">← Volver a la tienda</a>

</body>
</html>
