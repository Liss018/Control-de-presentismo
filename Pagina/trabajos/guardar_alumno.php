<?php
include 'base_de_datos.php';

header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON

// Manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true); // Obtener datos JSON

    if ($data === null) {
        echo json_encode(['error' => 'Datos inválidos.']);
        exit();
    }

    // Aquí debes procesar los datos y guardar los datos del alumno
    // Supongamos que recibes un solo alumno a editar
    $id = $data['id'];
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $dni = $data['dni'];

    // Ejecutar consulta para actualizar los datos del alumno en la base de datos
    $sql = "UPDATE registro_alum SET nombre = ?, apellido = ?, dni = ? WHERE id_alum = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['error' => 'Error en la preparación de la consulta: ' . $conexion->error]);
        exit();
    }

    $stmt->bind_param("sssi", $nombre, $apellido, $dni, $id);

    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Error al actualizar los datos: ' . $stmt->error]);
        exit();
    }

    echo json_encode(['success' => 'Datos actualizados correctamente.']);
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
