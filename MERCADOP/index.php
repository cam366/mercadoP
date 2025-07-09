<?php
include 'conexion.php';
session_start();

$resultado = $conexion->query("SELECT * FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Tienda - Mercado Libre Estilo</title>
<link rel="stylesheet" href="css/estilos.css">

</head>
<body>
  <div class="header">
    <div class="logo">Mercado Libre</div>
    <div class="search-bar"><input type="text" placeholder="Buscar..."></div>
    <div class="nav-links">
      <a href="carrito.php">🛒 Carrito (<?php echo count($_SESSION['carrito'] ?? []); ?>)</a>
    </div>
  </div>

  <div class="products">
    <?php while($row = $resultado->fetch_assoc()): ?>
    <div class="product-card">
      <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
      <h3><?php echo $row['nombre']; ?></h3>
      <p>$<?php echo number_format($row['precio'], 0, ',', '.'); ?></p>
      <form method="POST" action="agregar_al_carrito.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <button class="buy-btn">Agregar al carrito</button>
      </form>
    </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
