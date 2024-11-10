<?php
$pruebanombre = "Rodolfo Campos";
$pruebacasa = "Panama";
$pruebacelular = "64403577";
$pruebacorreo = "rodolfo.campos1@utp.ac.pa";

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30);
}

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

   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $verify_cart->execute([$user_id]);
   
   if(isset($_GET['get_id'])){

      $get_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $get_product->execute([$_GET['get_id']]);
      if($get_product->rowCount() > 0){
         while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)){
            $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([create_unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
            header('location:orders.php');
         }
      }else{
         $warning_msg[] = '¡Algo salió mal!';
      }

   }elseif($verify_cart->rowCount() > 0){

      while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)){

         $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
         $insert_order->execute([create_unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);

      }

      if($insert_order){
         $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart_id->execute([$user_id]);
         header('location:orders.php');
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

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/header.php'; ?>

<section class="checkout">

   <h1 class="heading">resumen de compra</h1>

   <div class="row">

      <form action="" method="POST">
         <h3>detalles de facturación</h3>
         <div class="flex">
            <div class="box">
               <p>tu nombre <span>*</span></p>
               <input type="text" name="name" required maxlength="50" placeholder="Ingresa tu nombre" class="input" <?php echo'value="'.$pruebanombre.'"'?>>
               <p>tu número <span>*</span></p>
               <input type="number" name="number" required maxlength="10" placeholder="Ingresa tu número" class="input" min="0" max="9999999999" <?php echo'value="'.$pruebacelular.'"'?>>
               <p>tu correo electrónico <span>*</span></p>
               <input type="email" name="email" required maxlength="50" placeholder="Ingresa tu correo electrónico" class="input" <?php echo'value="'.$pruebacorreo.'"'?>>
               <p>método de pago <span>*</span></p>
               <select name="method" class="input" required>
                  <option value="credit or debit card">tarjeta de crédito o débito</option>
                  <option value="net banking">banca en línea</option>  
                  <option value="cash on delivery">contra reembolso</option>
               </select>
               <p>tipo de dirección <span>*</span></p>
               <select name="address_type" class="input" required> 
                  <option value="home">casa</option>
                  <option value="office">oficina</option>
               </select>
            </div>
            <div class="box">
               <p>línea de dirección 01 <span>*</span></p>
               <input type="text" name="flat" required maxlength="50" placeholder="Ej. número de departamento y edificio" class="input" <?php echo'value="'.$pruebacasa.'"'?>>
               <p>línea de dirección 02 <span>*</span></p>
               <input type="text" name="street" required maxlength="50" placeholder="Ej. nombre de la calle y localidad" class="input">
               <p>nombre de la ciudad <span>*</span></p>
               <input type="text" name="city" required maxlength="50" placeholder="Ingresa el nombre de tu ciudad" class="input">
               <p>nombre del país <span>*</span></p>
               <input type="text" name="country" required maxlength="50" placeholder="Ingresa el nombre de tu país" class="input">
               <p>código postal <span>*</span></p>
               <input type="number" name="pin_code" required maxlength="6" placeholder="Ej. 123456" class="input" min="0" max="999999">
            </div>
         </div>
         <input type="submit" value="realizar pedido" name="place_order" class="btn">
      </form>

      <div class="summary">
         <h3 class="title">artículos en el carrito</h3>
         <?php
            $grand_total = 0;
            if(isset($_GET['get_id'])){
               $select_get = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
               $select_get->execute([$_GET['get_id']]);
               while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="flex">
            <img src="uploaded_files/<?= $fetch_get['image']; ?>" class="image" alt="">
            <div>
               <h3 class="name"><?= $fetch_get['name']; ?></h3>
               <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_get['price']; ?> x 1</p>
            </div>
         </div>
         <?php
               }
            }else{
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                     $select_products->execute([$fetch_cart['product_id']]);
                     $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                     $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);

                     $grand_total += $sub_total;
            
         ?>
         <div class="flex">
            <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="">
            <div>
               <h3 class="name"><?= $fetch_product['name']; ?></h3>
               <p class="price"><i class="fas fa-dollar-sign"></i> <?= $fetch_product['price']; ?> x <?= $fetch_cart['qty']; ?></p>
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

<script src="js/script.js"></script>

<?php include 'components/alert.php'; ?>

</body>
</html>
