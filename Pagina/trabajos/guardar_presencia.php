<?php
// guardar_presencia.php
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

$id_alumno = $input['id_alumno'];
$codigo = $input['codigo'];

// Conexión a la base de datos
// ... (aquí tu código para conectar a la base de datos)

if ($id_alumno && $codigo) {
    // Lógica para guardar la presencia en la base de datos
    // Por ejemplo:
    $sql = "UPDATE alumnos SET estado = 'Presente' WHERE id_alum = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_alumno);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

$conexion->close();
?>
