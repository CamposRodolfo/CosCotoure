<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'connect.php';

header("Content-Type: application/json");

// Verificar si los datos fueron enviados correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar que se haya recibido un array
    if (!is_array($data)) {
        echo json_encode(["error" => "El formato del cuerpo debe ser un array"]);
        exit;
    }

    // Procesar cada producto
    foreach ($data as $producto) {
        if (!isset($producto['nombre_producto'], $producto['precio'], $producto['imagen_producto'], $producto['creado_por'])) {
            echo json_encode(["error" => "Datos incompletos en uno de los productos"]);
            exit;
        }

        $nombre_producto = filter_var($producto['nombre_producto'], FILTER_SANITIZE_STRING);
        $precio = filter_var($producto['precio'], FILTER_VALIDATE_FLOAT);
        $imagen_producto = filter_var($producto['imagen_producto'], FILTER_SANITIZE_STRING);
        $creado_por = filter_var($producto['creado_por'], FILTER_VALIDATE_INT);

        // Verificar que los datos sean correctos
        if ($precio === false || $creado_por === false) {
            echo json_encode(["error" => "Precio o creado_por inválidos en uno de los productos"]);
            exit;
        }

        // Inserción en la base de datos
        $query = $conn->prepare("INSERT INTO Productos (nombre_producto, precio, imagen_producto, creado_por) VALUES (?, ?, ?, ?)");
        $result = $query->execute([$nombre_producto, $precio, $imagen_producto, $creado_por]);

        if (!$result) {
            echo json_encode(["error" => "Error al insertar el producto: $nombre_producto"]);
            exit;
        }
    }

    echo json_encode(["success" => "Productos insertados correctamente"]);
} else {
    echo json_encode(["error" => "Método no permitido"]);
    http_response_code(405); // Código de método no permitido
}
?>