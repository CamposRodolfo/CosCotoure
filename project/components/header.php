<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
   <section class="flex">
      <a href="#" class="logo">COSCOTOURE</a>

      <nav class="navbar">
         <a href="add_product.php">Añadir producto</a>
         <a href="view_products.php">Ver productos</a>
         <a href="orders.php">Mis pedidos</a>
         
         <?php
            // Prepara una consulta para contar los artículos en el carrito del usuario actual
            $count_cart_items = $conn->prepare("SELECT * FROM `Carros` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);  // Ejecuta la consulta usando el ID del usuario actual
            $total_cart_items = $count_cart_items->rowCount();  // Cuenta el número de filas devueltas (número de artículos en el carrito)
         ?>

         <?php
         if (isset($_SESSION['user_id'])) {
            echo '<a href="logout.php" class="btn-logout">Cerrar sesión</a>';
         } else {
            echo '<a href="login.php" class="btn-login">Iniciar sesión</a>';
         }
         ?>
         <a href="shopping_cart.php" class="cart-btn">
            Carrito <span><?= $total_cart_items ?? 0; ?></span>
         </a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>
   </section>
</header>
