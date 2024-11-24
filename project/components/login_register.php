<?php
session_start();
include 'connect.php'; // Conexión a la base de datos


// Manejo de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $check_user->execute([$email]);

    if ($check_user->rowCount() > 0) {
        $error_msg = 'El correo ya está registrado.';
    } else {
        $user_id = create_unique_id();
        $insert_user = $conn->prepare("INSERT INTO `users` (id, name, email, password) VALUES (?, ?, ?, ?)");
        $insert_user->execute([$user_id, $name, $email, $password]);
        header('Location: ../login.php'); // Redirige al inicio de sesión
        exit;
    }
}

// Manejo de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $get_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $get_user->execute([$email]);

    if ($get_user->rowCount() > 0) {
        $user = $get_user->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: ../view_products.php');
            exit;
        } else {
            $error_msg = 'Contraseña incorrecta.';
        }
    } else {
        $error_msg = 'Usuario no encontrado.';
    }
}
?>

<?php if (!empty($error_msg)): ?>
    <div class="error-msg"><?= htmlspecialchars($error_msg); ?></div>
<?php endif; ?>
