<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: view_products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Inicio de sesion</title>
</head>

<body>

    <div class="container" id="container">
        <!-- Formulario de Registro -->
        <div class="form-container sign-up">
            <form method="POST" action="components/login_register.php">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Nombre" required>
                <input type="email" name="email" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="register">Registrarse</button>
                <?php if (isset($error_msg)) echo "<p>$error_msg</p>"; ?>
            </form>
        </div>

        <!-- Formulario de Inicio de Sesión -->
        <div class="form-container sign-in">
            <form method="POST" action="components/login_register.php">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <input type="email" name="email" placeholder="Correo" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="login">Iniciar Sesión</button>
                <?php if (isset($error_msg)) echo "<p>$error_msg</p>"; ?>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>
