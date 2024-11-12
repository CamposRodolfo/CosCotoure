<?php

include 'components/connect.php';

// Verificar o establecer cookie de usuario
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    setcookie('user_id', create_unique_id(), time() + 60 * 60 * 24 * 30);
}

// Procesar formulario de agregar producto
if (isset($_POST['add'])) {

    // Sanitizar y validar entradas
    $id = create_unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = '../project_assets/uploaded_files/';

    // Validar tamaño de imagen y existencia de directorio
    if ($image_size > 2000000) {
        $warning_msg[] = '¡El tamaño de la imagen es demasiado grande!';
    } elseif (!is_dir($image_folder)) {
        mkdir($image_folder, 0777, true); // Crear directorio si no existe
    }

    // Intentar mover archivo y guardar en la base de datos
    if (empty($warning_msg)) {
        if (move_uploaded_file($image_tmp_name, $image_folder . $rename)) {
            try {
                $add_product = $conn->prepare("INSERT INTO `products`(id, name, price, image) VALUES(?,?,?,?)");
                $add_product->execute([$id, $name, $price, $rename]);
                $success_msg[] = '¡Producto agregado exitosamente!';
            } catch (PDOException $e) {
                $error_msg[] = 'Error al guardar en la base de datos: ' . $e->getMessage();
            }
        } else {
            $error_msg[] = 'Error al mover el archivo. Verifica permisos.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/header.php'; ?>

<section class="product-form">
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Información del producto</h3>
        <p>Nombre del producto <span>*</span></p>
        <input type="text" name="name" placeholder="Ingrese el nombre del producto" required maxlength="50" class="box">
        <p>Precio del producto <span>*</span></p>
        <input type="number" name="price" placeholder="Ingrese el precio del producto" required min="0" max="10000000" maxlength="8" class="box">
        <p>Imagen del producto <span>*</span></p>
        <input type="file" name="image" required accept="image/*" class="box">
        <input type="submit" class="btn" name="add" value="Agregar Producto">
    </form>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/script.js"></script>

<?php include 'components/alert.php'; ?>

</body>
</html>
