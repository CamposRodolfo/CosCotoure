<?php

include '../components/connect.php';


if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:lista_pedidos.php');
}

if(isset($_POST['cancel'])){

   $update_orders = $conn->prepare("UPDATE Pedidos SET estado = ? WHERE id = ?");
   $update_orders->execute(['canceled', $get_id]);
   header('location:orders.php');

   // Detectar si es URL o archivo local
   $imagen_producto = filter_var($fetch_producto['imagen_producto'], FILTER_VALIDATE_URL) ? 
                     $fetch_producto['imagen_producto'] : 
                     "../assets/img/archivos_subidos/" . $fetch_producto['imagen_producto'];

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ver Pedidos</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

   <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
   
<?php include '../components/header.php'; ?>

<section class="order-details">

   <h1 class="heading">detalles del pedido</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_pedidos = $conn->prepare("SELECT * FROM Pedidos WHERE id_pedido = ? LIMIT 1");
      $select_pedidos->execute([$get_id]);
      if($select_pedidos->rowCount() > 0){
         while($fetch_pedido = $select_pedidos->fetch(PDO::FETCH_ASSOC)){
            $select_producto = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ? LIMIT 1");
            $select_producto->execute([$fetch_pedido['id_producto']]);
            if($select_producto->rowCount() > 0){
               while($fetch_producto = $select_producto->fetch(PDO::FETCH_ASSOC)){
                  $sub_total = ($fetch_pedido['precio'] * $fetch_pedido['cantidad']);
                  $grand_total += $sub_total;
   ?>
   <div class="box">
      <div class="col">
         <p class="title"><i class="fas fa-calendar"></i><?= $fetch_pedido['actualizado_en']; ?></p>
         <img src="../assets/img/archivos_subidos/<?= $fetch_producto['imagen_producto']; ?>" class="image" alt="">
         <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_pedido['precio']; ?> x <?= $fetch_pedido['cantidad']; ?></p>
         <h3 class="name"><?= $fetch_producto['name']; ?></h3>
         <p class="grand-total">total : <span><i class="fas fa-dollar-sign"></i> <?= $grand_total; ?></span></p>
      </div>
      <div class="col">
         <p class="title">dirección de facturación</p>
         <p class="user"><i class="fas fa-user"></i><?= $fetch_pedido['name']; ?></p>
         <p class="user"><i class="fas fa-phone"></i><?= $fetch_pedido['number']; ?></p>
         <p class="user"><i class="fas fa-envelope"></i><?= $fetch_pedido['email']; ?></p>
         <p class="user"><i class="fas fa-map-marker-alt"></i><?= $fetch_pedido['address']; ?></p>
         <p class="title">estado</p>
         <p class="status" style="color:<?php if($fetch_pedido['estado'] == 'delivered'){echo 'green';}elseif($fetch_pedido['estado'] == 'cancelado'){echo 'red';}else{echo 'orange';}; ?>">
            <?php 
               if ($fetch_pedido['estado'] == 'entregado') {
                  echo 'entregado';
               } elseif ($fetch_pedido['estado'] == 'cancelado') {
                  echo 'cancelado';
               } else {
                  echo 'en proceso';
               }
            ?>
         </p>
         <?php if($fetch_pedido['estado'] == 'canceled'){ ?>
            <a href="checkout.php?get_id=<?= $fetch_producto['id']; ?>" class="btn">ordenar de nuevo</a>
         <?php }else{ ?>
         <form action="" method="POST">
            <input type="submit" value="cancelar pedido" name="cancel" class="delete-btn" onclick="return confirm('¿Cancelar este pedido?');">
         </form>
         <?php } ?>
      </div>
   </div>
   <?php
            }
         }else{
            echo '<p class="empty">¡Producto no encontrado!</p>';
         }
      }
   }else{
      echo '<p class="empty">¡No se encontraron pedidos!</p>';
   }
   ?>

   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="../assets/js/script.js"></script>

<?php include '../components/alert.php'; ?>

</body>
</html>
