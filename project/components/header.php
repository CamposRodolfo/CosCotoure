<?php
session_start();
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dropdown Menu 10</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/header.css">
</head>
<body>
    <header>
        <span class="navigation__group">
            <img class="icon" src="../../project_assets/header/message.svg" alt="Message">
            <div class="notification__wrapper">
                <img class="icon" src="../../project_assets/header/notification.svg" alt="Notification">
                <div class="notification__count"></div>
            </div>
            <button type="button" class="profile__btn">
    
                <img  src="../../project_assets/header/Usuario.png" alt="Usuario">
            </button>
        </span>
        <!-- Dropdown Menu -->
        <div class="dropdown__wrapper hide dropdown__wrapper--fade-in none" id="dropdown-menu">
            <nav>
                <div class="profile__wrapper">
                    <img class="profile__avatar" src="../../project_assets/header/Gabriel.jpg" alt="<?php echo htmlspecialchars($nombreUsuario); ?>">
                    <div class="profile__info">
                        <h2 class="profile__name"><?php echo htmlspecialchars($nombreUsuario); ?></h2>
                        <p class="profile__email"><?php echo htmlspecialchars($correoUsuario); ?></p>
                    </div>
                </div>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="profile.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                <path d="M15 19l2 2l4 -4" />
                            </svg>    
                            Account
                        </a>
                    </li>
                    <li><a href="integrations.php">Integrations</a></li>
                    <li><a href="settings.php">Settings</a></li>
                    <li><a href="guide.php">Guide</a></li>
                    <li><a href="help.php">Help</a></li>
                </ul>
                <hr class="divider">
                <div class="plan">
                    <div class="plan__description">
                        <h2>Free Plan</h2>
                        <p>1500 credits</p>
                    </div>
                    <a href="#" title="Upgrade Plan" class="upgrade-btn">Upgrade</a>
                </div>
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
                                Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <nav class="navbar">
            <a href="agregar_producto.php">Añadir producto</a>
            <a href="index.php">Ver productos</a>
            <a href="detalle_pedido.php">Mis pedidos</a>

            <a href="carro_compras.php" class="cart-btn">
                Carrito <span><?= $total_cart_items ?? 0; ?></span>
            </a>
            
        </nav>
    </header>

    <script src="../assets/js/header.js"></script>
</body>
</html>
