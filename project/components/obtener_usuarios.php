<?php
header("Content-Type: application/json; charset=UTF-8");

// Incluir el archivo de conexión
include_once 'connect.php';

// Verificar si se pasó un id_usuario en la URL (por ejemplo: api.php?id_usuario=1)
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

// Si se pasa un id_usuario, consultamos ese usuario específico
if ($id_usuario) {
    $query = "SELECT id_usuario, nombre, correo, telefono, ciudad, pais, tipo_usuario, creado_en FROM Usuarios WHERE id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
} else {
    // Si no se pasa id_usuario, traemos todos los usuarios
    $query = "SELECT id_usuario, nombre, correo, telefono, ciudad, pais, tipo_usuario, creado_en FROM Usuarios";
    $stmt = $conn->prepare($query);
}

$stmt->execute();

// Comprobar si hay resultados
if ($stmt->rowCount() > 0) {
    $usuarios = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $usuario_item = [
            "id_usuario" => $id_usuario,
            "nombre" => $nombre,
            "correo" => $correo,
            "telefono" => $telefono,
            "ciudad" => $ciudad,
            "pais" => $pais,
            "tipo_usuario" => $tipo_usuario,
            "creado_en" => $creado_en
        ];
        array_push($usuarios, $usuario_item);
    }

    // Devolver la respuesta en formato JSON
    echo json_encode([
        "status" => true,
        "data" => $usuarios
    ]);
} else {
    // Si no hay usuarios
    echo json_encode([
        "status" => false,
        "message" => "No se encontraron usuarios."
    ]);
}
?>
