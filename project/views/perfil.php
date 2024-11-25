<?php

include '../components/connect.php';

if (isset($_POST['place_order'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $flat = filter_var($_POST['flat'], FILTER_SANITIZE_STRING);
    $street = filter_var($_POST['street'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    $pin_code = filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING);
    $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    try {
        $update_user = $conn->prepare("UPDATE Usuarios SET 
            nombre = ?, 
            telefono = ?, 
            correo = ?, 
            direccion1 = ?, 
            direccion2 = ?, 
            ciudad = ?, 
            pais = ?, 
            codigo_postal = ?, 
            metodo_pago = ?, 
            tipo_direccion = ? 
            WHERE id_usuario = ?");
        
        $update_user->execute([
            $name,
            $number,
            $email,
            $flat,
            $street,
            $city,
            $country,
            $pin_code,
            $method,
            $address_type,
            $_SESSION['id_usuario']
        ]);

        $_SESSION['nombre_usuario'] = $name;
        $_SESSION['telefono'] = $number;
        $_SESSION['correo_usuario'] = $email;
        $_SESSION['direccion1'] = $flat;
        $_SESSION['direccion2'] = $street;
        $_SESSION['ciudad'] = $city;
        $_SESSION['pais'] = $country;
        $_SESSION['codigo_postal'] = $pin_code;
        $_SESSION['metodo_pago'] = $method;
        $_SESSION['tipo_direccion'] = $address_type;

        $success_msg[] = '¡Datos actualizados con éxito!';
    } catch (PDOException $e) {
        $error_msg[] = 'Error al actualizar los datos: ' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Datos del Usuario</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="checkout">
    <h1 class="heading">Actualizar Datos del Usuario</h1>

    <div class="row">
        <form action="" method="POST">
            <h3>Datos del Usuario</h3>
            <div class="flex">
            <div class="box">
               <p>tu nombre <span>*</span></p>
               <input type="text" name="name" required maxlength="50" placeholder="Ingresa tu nombre" class="input" <?php echo'value="'.$_SESSION['nombre_usuario'].'"'?>>
               <p>tu número <span>*</span></p>
               <input type="number" name="number" required maxlength="10" placeholder="Ingresa tu número" class="input" min="0" max="9999999999" <?php echo'value="'.$_SESSION['telefono'].'"'?>>
               <p>tu correo electrónico <span>*</span></p>
               <input type="email" name="email" required maxlength="50" placeholder="Ingresa tu correo electrónico" class="input" <?php echo'value="'.$_SESSION['correo_usuario'].'"'?>>
               <p>método de pago <span>*</span></p>
               <select name="method" class="input" <?php echo'value="'.$_SESSION['metodo_pago'].'"'?> required>
                  <option value="credit or debit card">tarjeta de crédito o débito</option>
                  <option value="net banking">banca en línea</option>  
                  <option value="cash on delivery">contra reembolso</option>
               </select>
               <p>tipo de dirección <span>*</span></p>
               <select name="address_type" class="input" <?php echo'value="'.$_SESSION['tipo_direccion'].'"'?> required> 
                  <option value="home">casa</option>
                  <option value="office">oficina</option>
               </select>
            </div>
            <div class="box">
               <p>línea de dirección 01 <span>*</span></p>
               <input type="text" name="flat" required maxlength="50" placeholder="Ej. número de departamento y edificio" class="input" <?php echo'value="'.$_SESSION['direccion1'].'"'?>>
               <p>línea de dirección 02 <span>*</span></p>
               <input type="text" name="street" required maxlength="50" placeholder="Ej. nombre de la calle y localidad" class="input" <?php echo'value="'.$_SESSION['direccion2'].'"'?>>
               <p>nombre de la ciudad <span>*</span></p>
               <input type="text" name="city" required maxlength="50" placeholder="Ingresa el nombre de tu ciudad" class="input" <?php echo'value="'.$_SESSION['ciudad'].'"'?>>
               <p>nombre del país <span>*</span></p>
               <input type="text" name="country" required maxlength="50" placeholder="Ingresa el nombre de tu país" class="input" <?php echo'value="'.$_SESSION['pais'].'"'?>>
               <p>código postal <span>*</span></p>
               <input type="number" name="pin_code" required maxlength="6" placeholder="Ej. 123456" class="input" min="0" max="999999" <?php echo'value="'.$_SESSION['codigo_postal'].'"'?>>
            </div>
         </div>
         <input type="submit" value="realizar pedido" name="place_order" class="btn">
      </form>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../assets/js/script.js"></script>

<?php include '../components/alert.php'; ?>

</body>
</html>
