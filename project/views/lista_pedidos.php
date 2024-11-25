<?php

include '../components/connect.php';

$id_usuario = '1';

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mis Pedidos</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

   <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
   
<?php include '../components/header.php'; ?>

<section class="orders">

   <h1 class="heading">mis pedidos</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM Pedidos WHERE id_usuario = ? ORDER BY actualizado_en DESC");
      $select_orders->execute([$id_usuario]);
      if($select_orders->rowCount() > 0){
         while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $select_product = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
            $select_product->execute([$fetch_order['id_producto']]);
            if($select_product->rowCount() > 0){
               while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" <?php if($fetch_order['estado'] == 'cancelado'){echo 'style="border:.2rem solid red";';}; ?>>
      <a href="view_order.php?get_id=<?= $fetch_order['id_pedido']; ?>">
         <p class="date"><i class="fa fa-calendar"></i><span><?= $fetch_order['actualizado_en']; ?></span></p>
         <img src="../assets/img/archivos_subidos/<?= $fetch_product['imagen_producto']; ?>" class="image" alt="">
         <h3 class="name"><?= $fetch_product['nombre_producto']; ?></h3>
         <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_order['precio']; ?> x <?= $fetch_order['cantidad']; ?></p>
         <p class="status" style="color:<?php if($fetch_order['estado'] == 'entregado'){echo 'green';}elseif($fetch_order['estado'] == 'cancelado'){echo 'red';}else{echo 'orange';}; ?>">
            <?php 
               if ($fetch_order['estado'] == 'entregado') {
                  echo 'entregado';
               } elseif ($fetch_order['estado'] == 'cancelado') {
                  echo 'cancelado';
               } else {
                  echo 'en proceso';
               }
            ?>
         </p>
      </a>
   </div>
   <?php
            }
         }
      }
   }else{
      echo '<p class="empty">¡no se encontraron pedidos!</p>';
   }
   ?>

   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="js/script.js"></script>

<?php include '../components/alert.php'; ?>

</body>
</html>
