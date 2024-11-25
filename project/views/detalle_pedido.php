<?php
include('../components/connect.php'); // Asegúrate de que este archivo tenga la conexión correcta.

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    die("No se especificó el ID del pedido.");
}

// Consulta del pedido
$query = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
$query->bind_param("i", $order_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("El pedido no existe.");
}

$pedido = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <?php include('header.php'); ?>
</header>
<section class="order-details">
    <div class="box-container">
        <div class="box">
            <h2>Detalles Del Pedido</h2>
            <div class="col">
                <img src="ruta_imagen/<?= $pedido['imagen'] ?>" alt="Producto">
                <p>Producto: <?= htmlspecialchars($pedido['producto']) ?></p>
                <p>Precio: $<?= number_format($pedido['precio'], 2) ?></p>
                <p>Cantidad: <?= $pedido['cantidad'] ?></p>
                <p>Total: $<?= number_format($pedido['precio'] * $pedido['cantidad'], 2) ?></p>
            </div>
            <button class="delete-btn" onclick="confirmarCancelacion(<?= $pedido['id'] ?>)">Cancelar Pedido</button>
        </div>
    </div>
</section>
<script>
function confirmarCancelacion(orderId) {
    if (confirm("¿Cancelar este pedido?")) {
        window.location.href = `cancelar_pedido.php?id=${orderId}`;
    }
}
</script>
</body>
</html>
