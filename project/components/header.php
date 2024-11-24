<header class="header">
   <section class="flex">
      <a href="#" class="logo">Logo</a>

      <nav class="navbar">
         <a href="add_product.php">Añadir producto</a>
         <a href="view_products.php">Ver productos</a>
         <a href="orders.php">Mis pedidos</a>

         <?php
         session_start();
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
