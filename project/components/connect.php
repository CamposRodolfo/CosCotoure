<?php

   // Definición de las credenciales de conexión a la base de datos
   $db_name = 'mysql:host=localhost;port=3306;dbname=CosCotoure_db';  // Especifica el host (localhost), el puerto (3307) y el nombre de la base de datos (shop_db)
   $db_user_name = 'root';   // Nombre de usuario de la base de datos
   $db_user_pass = '';       // Contraseña del usuario de la base de datos

   // Creación de una instancia PDO para conectar con la base de datos MySQL
   try {
       $conn = new PDO($db_name, $db_user_name, $db_user_pass);
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       die("Error al conectar con la base de datos: " . $e->getMessage());
   }

   // Definición de una función para generar un ID único de 20 caracteres aleatorios
   function create_unique_id(){
    // Define un conjunto de caracteres que se usarán para generar el ID
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters); // Calcula la longitud del conjunto de caracteres
    $randomString = '';  // Inicializa la cadena que contendrá el ID generado

    // Bucle para generar una cadena de 20 caracteres aleatorios
    for ($i = 0; $i < 20; $i++) {
        // Selecciona un carácter aleatorio del conjunto y lo añade a la cadena
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    // Devuelve el ID generado
    return $randomString;
}

?>
