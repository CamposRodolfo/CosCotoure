<?php

include '../components/connect.php';

if (isset($_POST['place_order'])) {

    // Sanitizar y procesar los datos del formulario
    $address = $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    // Consultar el carrito del usuario
    $verify_cart = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
    $verify_cart->execute([$_SESSION['id_usuario']]);

    if ($verify_cart->rowCount() > 0) {
        // Calcular el total del pedido
        $grand_total = 0;
        $cart_items = $verify_cart->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cart_items as $item) {
            $select_product = $conn->prepare("SELECT precio FROM Productos WHERE id_producto = ?");
            $select_product->execute([$item['id_producto']]);
            $product = $select_product->fetch(PDO::FETCH_ASSOC);
            $grand_total += $product['precio'] * $item['cantidad'];
        }

        // Insertar en la tabla Pedidos
        echo $pedido['id_producto']; // Línea donde ocurre el error
        $insert_pedido = $conn->prepare("INSERT INTO Pedidos (id_usuario, estado, total, direccion, tipo_direccion, metodo_pago) VALUES (?, 'en proceso', ?, ?, ?, ?)");
        $insert_pedido->execute([
            $_SESSION['id_usuario'],
            $grand_total,
            $address,
            $address_type,
            $method
        ]);
   
        $id_pedido = $conn->lastInsertId();

        // Insertar detalles en la tabla DetallePedido
        foreach ($cart_items as $item) {
            $select_product = $conn->prepare("SELECT precio FROM Productos WHERE id_producto = ?");
            $select_product->execute([$item['id_producto']]);
            $product = $select_product->fetch(PDO::FETCH_ASSOC);

            $insert_detalle = $conn->prepare("INSERT INTO DetallePedido (id_pedido, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
            $insert_detalle->execute([
                $id_pedido,
                $item['id_producto'],
                $item['cantidad'],
                $product['precio']
            ]);
        }

        // Vaciar el carrito
        $delete_cart = $conn->prepare("DELETE FROM CarroCompras WHERE id_usuario = ?");
        $delete_cart->execute([$_SESSION['id_usuario']]);

        header('Location: lista_pedidos.php');
        exit;
    } else {
        $warning_msg[] = '¡Tu carrito está vacío!';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="checkout">
    <h1 class="heading">Resumen de compra</h1>
    <div class="row">
        <form action="" method="POST">
            <h3>Detalles de facturación</h3>
            <div class="flex">
                <!-- Formulario -->
                <!-- Mantén aquí los campos del formulario tal como ya los tienes -->
            </div>
            <input type="submit" value="Realizar pedido" name="place_order" class="btn">
        </form>
        <div class="summary">
            <h3 class="title">Artículos en el carrito</h3>
            <?php
            $grand_total = 0;
            $select_cart = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
            $select_cart->execute([$_SESSION['id_usuario']]);
            if ($select_cart->rowCount() > 0) {
                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $select_products = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
                    $select_products->execute([$fetch_cart['id_producto']]);
                    $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                    $grand_total += $fetch_product['precio'] * $fetch_cart['cantidad'];
                    ?>
                    <div class="flex">
                        <img src="<?= htmlspecialchars($fetch_product['imagen_producto']); ?>" class="image" alt="">
                        <div>
                            <h3 class="name"><?= $fetch_product['nombre_producto']; ?></h3>
                            <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_product['precio']; ?> x <?= $fetch_cart['cantidad']; ?></p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">¡Tu carrito está vacío!</p>';
            }
            ?>
        </div>
    </div>
</section>

<script src="../assets/js/script.js"></script>
</body>
</html>
