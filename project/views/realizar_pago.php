<?php

include '../components/connect.php';

if(isset($_POST['place_order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $address = $_POST['flat'].', '.$_POST['street'].', '.$_POST['city'].', '.$_POST['country'].' - '.$_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $address_type = $_POST['address_type'];
   $address_type = filter_var($address_type, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);

   $verify_cart = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
   $verify_cart->execute([$_SESSION['id_usuario']]);
   
   if(isset($_GET['get_id'])){

      $get_product = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ? LIMIT 1");
      $get_product->execute([$_GET['get_id']]);
      if($get_product->rowCount() > 0){
         while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)){
            /* Verificar con los atributos de las tablas Pedidos y DetallePedido de la base de datos */
            $insert_order = $conn->prepare("INSERT INTO Pedidos(id_usuario, name, number, email, address, address_type, method, id_producto, precio, cantidad) VALUES(?,?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([$_SESSION['id_usuario'], $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['precio'], 1]);
            header('location:lista_pedidos.php');
         }
      }else{
         $warning_msg[] = '¡Algo salió mal!';
      }

   }elseif($verify_cart->rowCount() > 0){

      while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)){

        /* Verificar con los atributos de las tablas Pedidos y DetallePedido de la base de datos */
         $insert_order = $conn->prepare("INSERT INTO Pedidos(id_usuario, name, number, email, address, address_type, method, id_producto, precio, cantidad) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
         $insert_order->execute([$_SESSION['id_usuario'], $name, $number, $email, $address, $address_type, $method, $f_cart['id_producto'], $f_cart['precio'], $f_cart['cantidad']]);

      }

      if($insert_order){
         $delete_cart_id = $conn->prepare("DELETE FROM CarroCompras WHERE id_usuario = ?");
         $delete_cart_id->execute([$_SESSION['id_usuario']]);
         header('location:lista_pedidos.php');
      }

   }else{
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

   <h1 class="heading">resumen de compra</h1>

   <div class="row">

      <form action="" method="POST">
         <h3>detalles de facturación</h3>
         <div class="flex">
            <div class="box">
               <p>tu nombre <span>*</span></p>
               <input type="text" name="name" required maxlength="50" placeholder="Ingresa tu nombre" class="input" <?php echo'value="'.$_SESSION['nombre_usuario'].'"'?>>
               <p>tu número <span>*</span></p>
               <input type="number" name="number" required maxlength="10" placeholder="Ingresa tu número" class="input" min="0" max="9999999999" <?php echo'value="'.$_SESSION['telefono'].'"'?>>
               <p>tu correo electrónico <span>*</span></p>
               <input type="email" name="email" required maxlength="50" placeholder="Ingresa tu correo electrónico" class="input" <?php echo'value="'.$_SESSION['correo_usuario'].'"'?>>
               <p>método de pago <span>*</span></p>
               <select name="method" class="input" <?php echo'value="'.$_SESSION['metodo_pago'].'"'?> required>
                  <option value="credit or debit card">tarjeta de crédito o débito</option>
                  <option value="net banking">banca en línea</option>  
                  <option value="cash on delivery">contra reembolso</option>
               </select>
               <p>tipo de dirección <span>*</span></p>
               <select name="address_type" class="input" <?php echo'value="'.$_SESSION['tipo_direccion'].'"'?> required> 
                  <option value="home">casa</option>
                  <option value="office">oficina</option>
               </select>
            </div>
            <div class="box">
               <p>línea de dirección 01 <span>*</span></p>
               <input type="text" name="flat" required maxlength="50" placeholder="Ej. número de departamento y edificio" class="input" <?php echo'value="'.$_SESSION['direccion1'].'"'?>>
               <p>línea de dirección 02 <span>*</span></p>
               <input type="text" name="street" required maxlength="50" placeholder="Ej. nombre de la calle y localidad" class="input" <?php echo'value="'.$_SESSION['direccion2'].'"'?>>
               <p>nombre de la ciudad <span>*</span></p>
               <input type="text" name="city" required maxlength="50" placeholder="Ingresa el nombre de tu ciudad" class="input" <?php echo'value="'.$_SESSION['ciudad'].'"'?>>
               <p>nombre del país <span>*</span></p>
               <input type="text" name="country" required maxlength="50" placeholder="Ingresa el nombre de tu país" class="input" <?php echo'value="'.$_SESSION['pais'].'"'?>>
               <p>código postal <span>*</span></p>
               <input type="number" name="pin_code" required maxlength="6" placeholder="Ej. 123456" class="input" min="0" max="999999" <?php echo'value="'.$_SESSION['codigo_postal'].'"'?>>
            </div>
         </div>
         <input type="submit" value="realizar pedido" name="place_order" class="btn">
      </form>

      <div class="summary">
         <h3 class="title">artículos en el carrito</h3>
         <?php
            $grand_total = 0;
            if(isset($_GET['get_id'])){
               $select_get = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
               $select_get->execute([$_GET['get_id']]);
               while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="flex">
            <img src="../assets/img/archivos_subidos/<?= $fetch_get['imagen_producto']; ?>" class="image" alt="">
            <div>
               <h3 class="name"><?= $fetch_get['nombre_producto']; ?></h3>
               <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_get['precio']; ?> x 1</p>
            </div>
         </div>
         <?php
               }
            }else{
               $select_cart = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
               $select_cart->execute([$_SESSION['id_usuario']]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $select_products = $conn->prepare("SELECT * FROM Productos WHERE id_producto = ?");
                     $select_products->execute([$fetch_cart['id_producto']]);
                     $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                     $sub_total = ($fetch_cart['cantidad'] * $fetch_product['precio']);

                     $grand_total += $sub_total;
            
         ?>
         <div class="flex">
            <img src="../assets/img/archivos_subidos/<?= $fetch_product['imagen_producto']; ?>" class="image" alt="">
            <div>
               <h3 class="name"><?= $fetch_product['nombre_producto']; ?></h3>
               <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_product['precio']; ?> x <?= $fetch_cart['cantidad']; ?></p>
            </div>
         </div>
         <?php
                  }
               }else{
                  echo '<p class="empty">tu carrito está vacío</p>';
               }
            }
         ?>
         <div class="grand-total"><span>total :</span><p><i class="fas fa-dollar-sign"></i> <?= $grand_total; ?></p></div>
      </div>

   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="../assets/js/script.js"></script>

<?php include '../components/alert.php'; ?>

</body>
</html>
