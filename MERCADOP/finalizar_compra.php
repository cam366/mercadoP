<?php
require __DIR__ . '/vendor/autoload.php';
include 'conexion.php';
session_start();

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

MercadoPagoConfig::setAccessToken('APP_USR-4304575057365924-062512-1576af0764c1d74a5445998e36e21d5a-1577049737');

$carrito = $_SESSION['carrito'] ?? [];

$productos = [];
$total = 0;

if ($carrito) {
    $ids = implode(',', array_map('intval', $carrito)); // Sanitize por seguridad
    $sql = "SELECT * FROM productos WHERE id IN ($ids)";
    $result = $conexion->query($sql);

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
        $total += $row['precio'];
    }
}

// Creamos una preferencia en Mercado Pago con el total
$client = new PreferenceClient();

try {
    $preference = $client->create([
        "items" => [
            [
                "title" => "Compra en Tienda Online",
                "quantity" => 1,
                "unit_price" => floatval($total)
            ]
        ],
        "back_urls" => [
            "success" => "http://localhost/integramp/gracias.php",
            "failure" => "http://localhost/integramp/failure.php",
            "pending" => "http://localhost/integramp/pending.php"
        ]
    ]);

    $preferenceId = $preference->id;

} catch (MercadoPago\Exceptions\MPApiException $e) {
    echo "❌ Error de MercadoPago: " . $e->getMessage();
    echo "<pre>";
    print_r($e->getApiResponse());
    echo "</pre>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Mi Integración con Checkout Pro</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

  <h1>Confirmar pago por $<?php echo number_format($total, 0, ',', '.'); ?></h1>
  <div id="walletBrick_container"></div>

  <!-- SDK de Mercado Pago -->
  <script src="https://sdk.mercadopago.com/js/v2"></script>
  <script>
    // Usá tu PUBLIC KEY real
    const mp = new MercadoPago('APP_USR-a333355b-fb43-4069-9b66-81f3906804e4');

    const renderWalletBrick = async (bricksBuilder) => {
      await bricksBuilder.create("wallet", "walletBrick_container", {
        initialization: {
          preferenceId: "<?php echo $preferenceId; ?>",
        }
      });
    };

    const bricksBuilder = mp.bricks();
    renderWalletBrick(bricksBuilder);
  </script>

</body>
</html>
