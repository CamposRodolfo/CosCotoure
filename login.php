<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el correo existe
    $check_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_user->execute([$email]);

    if ($check_user->rowCount() > 0) {
        $user = $check_user->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            echo "Inicio de sesión exitoso. Bienvenido, " . $user['name'];
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "El correo no está registrado.";
    }
}
?>
