<?php
include 'project/components/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("Las contraseñas no coinciden.");
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $unique_id = create_unique_id();

    // Verificar si el correo ya está registrado
    $check_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_user->execute([$email]);

    if ($check_user->rowCount() > 0) {
        die("El correo ya está registrado.");
    }

    // Insertar nuevo usuario
    $insert_user = $conn->prepare("INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)");
    $insert_user->execute([$unique_id, $name, $email, $hashed_password]);

    echo "Usuario registrado exitosamente.";
}
?>
