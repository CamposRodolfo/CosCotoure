<!-- Listado de los Productos -->
<?php

include '../components/connect.php';

if(isset($_POST['add_to_cart'])){

    $id_producto = $_POST['id_producto'];
    $id_producto = filter_var($id_producto, FILTER_SANITIZE_STRING);
    $cantidad = $_POST['cantidad'];
    $cantidad = filter_var($cantidad, FILTER_SANITIZE_STRING);

    $verify_cart = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ? AND id_producto = ?");   
    $verify_cart->execute([$_SESSION['id_usuario'], $id_producto]);

    $max_cart_items = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
    $max_cart_items->execute([$_SESSION['id_usuario']]);

    if($verify_cart->rowCount() > 0){
        $warning_msg[] = '¡Ya está añadido al carrito!';
    }elseif($max_cart_items->rowCount() == 10){
        $warning_msg[] = '¡El carrito está lleno!';
    }else{
        $select_precio = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ? LIMIT 1");
        $select_precio->execute([$id_producto]);
        $fetch_precio = $select_precio->fetch(PDO::FETCH_ASSOC);

        $insert_cart = $conn->prepare("INSERT INTO CarroCompras(id_usuario, id_producto, precio, cantidad) VALUES(?,?,?,?)");
        $insert_cart->execute([$_SESSION['id_usuario'], $id_producto, $fetch_precio['precio'], $cantidad]);
        $success_msg[] = '¡Añadido al carrito!';
    }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ver Productos</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

   <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
   
<?php include '../components/header.php'; ?>

<section class="products">

   <h1 class="heading">Todos los Productos</h1>

   <div class="box-container">

   <?php 
      $select_Productos = $conn->prepare("SELECT * FROM Productos");
      $select_Productos->execute();
      if($select_Productos->rowCount() > 0){
         while($fetch_producto = $select_Productos->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="POST" class="box">
      <img src="../assets/img/archivos_subidos/<?= $fetch_producto['imagen_producto']; ?>" class="image" alt="">
      <h3 class="name"><?= $fetch_producto['nombre_producto'] ?></h3>
      <input type="hidden" name="id_producto" value="<?= $fetch_producto['id_producto']; ?>">
      <div class="flex">
         <p class="price"><i class="fas fa-dollar-sign"></i><?= $fetch_producto['precio'] ?></p>
         <input type="number" name="cantidad" required min="1" value="1" max="99" maxlength="2" class="qty">
      </div>
      <input type="submit" name="add_to_cart" value="Añadir al Carrito" class="btn">
      <a href="realizar_pago.php?get_id=<?= $fetch_producto['id_producto']; ?>" class="delete-btn">Comprar Ahora</a>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">¡No se encontraron productos!</p>';
   }
   ?>

   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="../assets/js/script.js"></script>

<?php include '../components/alert.php'; ?>

</body>
</html>
