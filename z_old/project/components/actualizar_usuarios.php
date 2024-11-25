<?php
header("Content-Type: application/json; charset=UTF-8");

// Incluir el archivo de conexión
include_once 'connect.php';

// Obtener el ID del usuario desde el parámetro en la URL 
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

// Comprobar si el ID de usuario fue proporcionado
if ($id_usuario) {
    // Obtener los datos enviados con la solicitud PUT (cuerpo de la solicitud)
    $input_data = json_decode(file_get_contents("php://input"), true);

    // Verificar si los campos a actualizar fueron enviados
    $telefono = isset($input_data['telefono']) ? $input_data['telefono'] : '12345678'; 
    $ciudad = isset($input_data['ciudad']) ? $input_data['ciudad'] : 'Panamá';  
    $pais = isset($input_data['pais']) ? $input_data['pais'] : 'Panamá';  

    // Preparar la consulta para actualizar los datos del usuario
    $query = "UPDATE Usuarios SET telefono = :telefono, ciudad = :ciudad, pais = :pais, actualizado_en = CURRENT_TIMESTAMP WHERE id_usuario = :id_usuario";

    // Preparar la sentencia
    $stmt = $conn->prepare($query);

    // Enlazar los parámetros
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':ciudad', $ciudad);
    $stmt->bindParam(':pais', $pais);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Devolver respuesta de éxito
        echo json_encode([
            "status" => true,
            "message" => "Usuario actualizado con éxito."
        ]);
    } else {
        // Si algo salió mal
        echo json_encode([
            "status" => false,
            "message" => "No se pudo actualizar el usuario."
        ]);
    }
} else {
    // Si no se proporciona el ID del usuario
    echo json_encode([
        "status" => false,
        "message" => "ID de usuario no proporcionado."
    ]);
}
?>
