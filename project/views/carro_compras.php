<?php

include '../components/connect.php';

if(isset($_POST['update_cart'])){

   $id_carro = $_POST['id_carro'];
   $id_carro = filter_var($id_carro, FILTER_SANITIZE_STRING);
   $cantidad = $_POST['cantidad'];
   $cantidad = filter_var($cantidad, FILTER_SANITIZE_STRING);

   $update_cantidad = $conn->prepare("UPDATE CarroCompras SET cantidad = ? WHERE id_carro = ?");
   $update_cantidad->execute([$cantidad, $id_carro]);

   $success_msg[] = '¡Cantidad actualizada en el carrito!';

}

if(isset($_POST['delete_item'])){

   $id_carro = $_POST['id_carro'];
   $id_carro = filter_var($id_carro, FILTER_SANITIZE_STRING);
   
   $verify_delete_item = $conn->prepare("SELECT * FROM CarroCompras WHERE id_carro = ?");
   $verify_delete_item->execute([$id_carro]);

   if($verify_delete_item->rowCount() > 0){
      $delete_id_carro = $conn->prepare("DELETE FROM CarroCompras WHERE id_carro = ?");
      $delete_id_carro->execute([$id_carro]);
      $success_msg[] = '¡Artículo eliminado del carrito!';
   }else{
      $warning_msg[] = '¡El artículo ya fue eliminado del carrito!';
   } 

}

if(isset($_POST['empty_cart'])){
   
   $verify_empty_cart = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
   $verify_empty_cart->execute([$_SESSION['id_usuario']]);

   if($verify_empty_cart->rowCount() > 0){
      $delete_id_carro = $conn->prepare("DELETE FROM CarroCompras WHERE id_usuario = ?");
      $delete_id_carro->execute([$_SESSION['id_usuario']]);
      $success_msg[] = '¡Carrito vaciado!';
   }else{
      $warning_msg[] = '¡El carrito ya está vacío!';
   } 

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Carrito de Compras</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

   <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
   
<?php include '../components/header.php'; ?>

<section class="products">

   <h1 class="heading">carrito de compras</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
      $select_cart->execute([$_SESSION['id_usuario']]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){

         $select_products = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
         $select_products->execute([$fetch_cart['id_producto']]);
         if($select_products->rowCount() > 0){
            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
               /* Imagen cargarla  */
               $imagen_producto = filter_var(
                 $fetch_product['imagen_producto'], 
                 FILTER_VALIDATE_URL
             ) ? $fetch_product['imagen_producto'] : 
             "../assets/img/archivos_subidos/" . htmlspecialchars($fetch_product['imagen_producto']);
?>   
   <form action="" method="POST" class="box">
   <img src="<?= htmlspecialchars($imagen_producto); ?>" class="image" alt="<?= htmlspecialchars($fetch_product['nombre_producto']); ?>">
   <h3 class="name"><?= $fetch_product['nombre_producto']; ?></h3>
      <div class="flex">
         <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_cart['precio']; ?></p>
         <input type="number" name="cantidad" required min="1" value="<?= $fetch_cart['cantidad']; ?>" max="99" maxlength="2" class="cantidad">
         <button type="submit" name="update_cart" class="fas fa-edit">
         </button>
      </div>
      <p class="sub-total">subtotal : <span><i class="fas fa-dollar-sign"></i> <?= $sub_total = ($fetch_cart['cantidad'] * $fetch_cart['precio']); ?></span></p>
      <input type="submit" value="eliminar" name="delete_item" class="delete-btn" onclick="return confirm('¿Eliminar este artículo?');">
   </form>
   <?php
      $grand_total += $sub_total;
      }else{
         echo '<p class="empty">¡Producto no encontrado!</p>';
      }
      }
   }else{
      echo '<p class="empty">¡Tu carrito está vacío!</p>';
   }
   ?>

   </div>

   <?php if($grand_total != 0){ ?>
      <div class="cart-total">
         <p>total : <span><i class="fas fa-dollar-sign"></i> <?= $grand_total; ?></span></p>
         <form action="" method="POST">
          <input type="submit" value="vaciar carrito" name="empty_cart" class="delete-btn" onclick="return confirm('¿Vaciar tu carrito?');">
         </form>
         <a href="realizar_pago.php" class="btn">proceder a la compra</a>
      </div>
   <?php } ?>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="../assets/js/script.js"></script>

<?php include '../components/alert.php'; ?>

</body>
</html>
