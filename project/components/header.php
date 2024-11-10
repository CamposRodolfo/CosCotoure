<header class="header">
   <section class="flex">
      <a href="#" class="logo">Logo</a>  <!-- Enlace al logotipo, que actualmente no dirige a ninguna página específica -->

      <nav class="navbar">
         <a href="add_product.php">añadir producto</a>  <!-- Enlace a la página para agregar productos -->
         <a href="view_products.php">ver productos</a>  <!-- Enlace a la página para ver los productos -->
         <a href="orders.php">mis pedidos</a>  <!-- Enlace a la página de pedidos del usuario -->
         
         <?php
            // Prepara una consulta para contar los artículos en el carrito del usuario actual
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);  // Ejecuta la consulta usando el ID del usuario actual
            $total_cart_items = $count_cart_items->rowCount();  // Cuenta el número de filas devueltas (número de artículos en el carrito)
         ?>
         <!-- Enlace al carrito de compras que muestra la cantidad total de artículos -->
         <a href="shopping_cart.php" class="cart-btn">carrito<span><?= $total_cart_items; ?></span></a>
      </nav>

      <!-- Icono para el menú de navegación móvil -->
      <div id="menu-btn" class="fas fa-bars"></div>
   </section>
</header>
