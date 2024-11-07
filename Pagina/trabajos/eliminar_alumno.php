<?php
include 'base_de_datos.php';
session_start();

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
    exit();
}

// Inicializar la respuesta
$response = ['success' => false];

// Obtener el ID del alumno a eliminar
$data = json_decode(file_get_contents("php://input"));

if (isset($data->id_alum)) {
    $id_alum = intval($data->id_alum); // Convertir a entero para evitar inyecciones

    // Preparar la consulta para eliminar al alumno de la tabla asistencia
    $sqlAsistencia = "DELETE FROM asistencia WHERE id_alum = ?";
    $stmtAsistencia = $conexion->prepare($sqlAsistencia);
    
    if ($stmtAsistencia) {
        $stmtAsistencia->bind_param("i", $id_alum);

        // Ejecutar la consulta para eliminar de asistencia
        if ($stmtAsistencia->execute()) {
            $stmtAsistencia->close(); // Cerrar el statement de asistencia

            // Preparar la consulta para eliminar al alumno de registro_alum
            $sqlAlumno = "DELETE FROM registro_alum WHERE id_alum = ?";
            $stmtAlumno = $conexion->prepare($sqlAlumno);
            
            if ($stmtAlumno) {
                $stmtAlumno->bind_param("i", $id_alum);

                // Ejecutar la consulta para eliminar al alumno
                if ($stmtAlumno->execute()) {
                    $response['success'] = true; // Eliminación exitosa
                } else {
                    $response['message'] = 'Error al eliminar el alumno: ' . $stmtAlumno->error;
                }
                $stmtAlumno->close();
            } else {
                $response['message'] = 'Error al preparar la consulta de alumno: ' . $conexion->error;
            }
        } else {
            $response['message'] = 'Error al eliminar de asistencia: ' . $stmtAsistencia->error;
        }
    } else {
        $response['message'] = 'Error al preparar la consulta de asistencia: ' . $conexion->error;
    }
} else {
    $response['message'] = 'ID del alumno no proporcionado.';
}

// Cerrar la conexión
$conexion->close();

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
