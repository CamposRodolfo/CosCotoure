<?php
header("Content-Type: application/json; charset=UTF-8");

// Incluir el archivo de conexión
include_once 'connect.php';

// Obtener el ID del usuario desde el parámetro en la URL (ejemplo: api.php?id_usuario=1)
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

// Comprobar si el ID de usuario fue proporcionado
if ($id_usuario) {
    // Preparar la consulta para eliminar el usuario
    $query = "DELETE FROM Usuarios WHERE id_usuario = :id_usuario";

    // Preparar la sentencia
    $stmt = $conn->prepare($query);

    // Enlazar el parámetro
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Devolver respuesta de éxito
        echo json_encode([
            "status" => true,
            "message" => "Usuario eliminado con éxito."
        ]);
    } else {
        // Si algo salió mal
        echo json_encode([
            "status" => false,
            "message" => "No se pudo eliminar el usuario."
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
