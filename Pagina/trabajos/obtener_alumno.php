<?php
include 'base_de_datos.php'; // Asegúrate de que la conexión a la base de datos se establezca correctamente
header('Content-Type: application/json');

$id = $_GET['id'];

// Asegúrate de que estás sanitizando el ID antes de usarlo en la consulta
if (isset($id) && is_numeric($id)) {
    $sql = "SELECT nombre, apellido, dni FROM registro_alum WHERE id_alum = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $alumno = $result->fetch_assoc();
        echo json_encode($alumno);
    } else {
        echo json_encode(["error" => "No se encontró el alumno."]);
    }
} else {
    echo json_encode(["error" => "ID inválido."]);
}

$conexion->close();
?>
