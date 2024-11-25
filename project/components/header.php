<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['logout'])) {
    session_unset(); // Eliminar todas las variables de sesión
    session_destroy(); // Destruir la sesión
    header('Location: ../views/inicio_sesion.php'); // Redirigir a la página de login después de cerrar sesión
    exit;
}

if (isset($_SESSION['nombre_usuario']) && isset($_SESSION['correo_usuario'])) {
    $nombreUsuario = $_SESSION['nombre_usuario'];
    $correoUsuario = $_SESSION['correo_usuario'];
}

// Prepara una consulta para contar los artículos en el carrito del usuario actual
$count_cart_items = $conn->prepare("SELECT * FROM CarroCompras WHERE id_usuario = ?");
$count_cart_items->execute([$_SESSION['id_usuario']]); 
$total_cart_items = $count_cart_items->rowCount(); // Cuenta el número de filas devueltas (número de artículos en el carrito)
?>

<head>
    <link rel="stylesheet" href="../assets/css/header.css">
</head>
<body>
    <header class="header">
        <section class="flex">
            <a href="index.php" class="logo">CosCotoure</a>

            <nav class="navbar">
                <a href="agregar_producto.php">Añadir producto</a>
                <a href="index.php">Ver productos</a>
                <a href="lista_pedidos.php">Mis pedidos</a>

                <a href="carro_compras.php" class="cart-btn">Carro de Compras<span><?= $total_cart_items ?? 0; ?></span></a>

            </nav>
        </section>

        <span class="navigation__group">
                <button type="button" class="profile__btn">
                    <img  src="../../project_assets/header/Usuario.png" alt="Usuario">
                </button>
            </span>
            <!-- Dropdown Menu -->
            <div class="dropdown__wrapper hide dropdown__wrapper--fade-in none" id="dropdown-menu">
                <nav>
                    <div class="profile__wrapper">
                        <img class="profile__avatar" src="../../project_assets/header/Usuario.png" alt="<?php echo htmlspecialchars($nombreUsuario); ?>">
                        <div class="profile__info">
                            <h2 class="profile__name"><?php echo htmlspecialchars($nombreUsuario); ?></h2>
                            <p class="profile__email"><?php echo htmlspecialchars($correoUsuario); ?></p>
                        </div>
                    </div>
                    <hr class="divider">
                    <ul>
                        <li>
                            <a href="perfil.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    <path d="M15 19l2 2l4 -4" />
                                </svg>    
                                Mi Cuenta
                            </a>
                        </li>
                    </ul>
                    <hr class="divider">
                    <ul>
                        <li>
                            <form action="" method="POST">
                                <button type="submit" name="logout" style="background: none; border: none; display: flex; align-items: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
    </header>
    <script src="../assets/js/header.js"></script>
</body>
</html>
