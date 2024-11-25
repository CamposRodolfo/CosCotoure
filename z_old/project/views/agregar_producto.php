<?php

include '../components/connect.php';

if(isset($_POST['add'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $precio = $_POST['precio'];
    $precio = filter_var($precio, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id().'.'.$ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = '../assets/img/archivos_subidos/'.$rename;

   if($image_size > 2000000){
        $warning_msg[] = '¡El tamaño de la imagen es demasiado grande!';
   }else{
        $add_product = $conn->prepare("INSERT INTO `Productos`(nombre_producto, precio, imagen_producto, creado_por) VALUES(?,?,?,?)");
        $add_product->execute([$name, $precio, $rename, $_SESSION['id_usuario']]); // Falta obtener la variable de usuario de la base de datos
        move_uploaded_file($image_tmp_name, $image_folder);
        $success_msg[] = '¡Producto agregado!';
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

   <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
   
<?php include '../components/header.php'; ?>

<section class="product-form">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Información del producto</h3>
      <p>nombre del producto <span>*</span></p>
      <input type="text" name="name" placeholder="Ingrese el nombre del producto" required maxlength="50" class="box">
      <p>precio del producto <span>*</span></p>
      <input type="number" name="precio" placeholder="Ingrese el precio del producto" required min="0" max="9999999999" maxlength="10" class="box">
      <p>imagen del producto <span>*</span></p>
      <input type="file" name="image" required accept="image/*" class="box">
      <input type="submit" class="btn" name="add" value="Agregar Producto">
   </form>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="../assets/js/script.js"></script>

<?php include '../components/alert.php'; ?>

</body>
</html>
