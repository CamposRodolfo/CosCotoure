<?php
session_start();
include '../components/connect.php'; // Conexión a la base de datos
$error_msg = ''; // Para almacenar mensajes de error
$success_msg = ''; // Mensaje de éxito

// Procesar el registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verificar si el correo ya está registrado
    $check_user = $conn->prepare("SELECT * FROM `Usuarios` WHERE correo = ?");
    $check_user->execute([$email]);
    if ($check_user->rowCount() > 0) {
        $error_msg = 'El correo ya está registrado.';
    } else {
        // Insertar nuevo usuario
        $insert_user = $conn->prepare("INSERT INTO `Usuarios` (nombre, correo, contrasena) VALUES (?, ?, ?)");
        $insert_user->execute([$name, $email, $password]);

        // Iniciar sesión automáticamente después de registrarse
        $get_user = $conn->prepare("SELECT * FROM `Usuarios` WHERE correo = ?");
        $get_user->execute([$email]);
        $user = $get_user->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id_usuario'];

        $success_msg = "¡Registro exitoso! Ahora puedes iniciar sesión.";
    }
}

// Procesar inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $get_user = $conn->prepare("SELECT * FROM `Usuarios` WHERE correo = ?");
    $get_user->execute([$email]);

    if ($get_user->rowCount() > 0) {
        $user = $get_user->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['contrasena'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['nombre_usuario'] = $user['nombre'];
            $_SESSION['correo_usuario'] = $user['correo'];
            header('Location: agregar_producto.php'); // Redirigir al dashboard
            exit;
        }
        else {
            $error_msg = 'Contraseña incorrecta.';
        }
    } else {
        $error_msg = 'Usuario no encontrado.';
    }
}

// Redirigir si el usuario ya está autenticado
if (isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) === 'login_register.php') {
    header('Location: agregar_producto.php'); // Redirigir al dashboard si está autenticado
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>Inicio de Sesión y Registro</title>
</head>

<body>

<div class="container" id="container">
        <!-- Formulario de Registro -->
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Crear Cuenta</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>o usa tu correo para registrarte</span>
                <input type="text" name="name" placeholder="Nombre" required>
                <input type="email" name="email" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="register">Registrarse</button>
                <?php 
                    if (isset($error_msg)) echo "<p>$error_msg</p>";
                    if (isset($success_msg)) echo "<p>$success_msg</p>";
                ?>
            </form>
        </div>

        <!-- Formulario de Inicio de Sesión -->
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Iniciar Sesión</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>o usa tu correo y contraseña</span>
                <input type="email" name="email" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="login">Iniciar Sesión</button>
                <?php if (isset($error_msg)) echo "<p>$error_msg</p>"; ?>
            </form>
        </div>
        
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Ingresa tus datos para usar todas las funciones del sitio</p>
                    <button class="hidden" id="login">Iniciar sesión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>¡Hola, amigo!</h1>
                    <p>Regístrate con tus datos personales para usar todas las funciones del sitio</p>
                    <button class="hidden" id="register">Regístrate</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div class="error-msg"><?= htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <script src="../assets/js/script.js"></script>
</body>

</html>
