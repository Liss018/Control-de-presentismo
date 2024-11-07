<?php
include 'base_de_datos.php'; // Incluye el archivo de conexión a la base de datos
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['admin_id'])) {
    // Si no está autenticado, redirigir al inicio de sesión
    header("Location: sesion_admi.html");
    exit();
}

// Obtener los valores enviados por POST
$id_alumno = isset($_POST['id_alumno']) ? intval($_POST['id_alumno']) : 0; // Obtiene el ID del alumno de la solicitud POST y lo convierte a entero
$codigo_ingresado = isset($_POST['codigo']) ? htmlspecialchars($_POST['codigo']) : ''; // Obtiene el código ingresado y lo sanitiza

// Consultar el código del alumno en la base de datos
$sql = "SELECT codigo FROM registro_alum WHERE id_alum = ?"; // Consulta para obtener el código del alumno
$stmt = $conexion->prepare($sql); // Prepara la consulta
$stmt->bind_param('i', $id_alumno); // Vincula el parámetro id_alumno
$stmt->execute(); // Ejecuta la consulta
$result = $stmt->get_result(); // Obtiene el resultado

if ($result->num_rows > 0) { // Si se encuentra el alumno
    $row = $result->fetch_assoc(); // Obtiene la fila de resultados
    $codigo_alumno = $row['codigo']; // Almacena el código del alumno

    // Comparar el código ingresado con el del alumno
    if ($codigo_ingresado === $codigo_alumno) { 
        // Obtener la fecha actual
        $fechaActual = date('Y-m-d'); // Fecha actual en formato YYYY-MM-DD

        // Verificar si ya existe un registro de asistencia para la fecha actual
        $check_sql = "SELECT * FROM asistencia WHERE id_alum = ? AND fecha = ?"; // Consulta para verificar asistencia
        $check_stmt = $conexion->prepare($check_sql); // Prepara la consulta
        $check_stmt->bind_param('is', $id_alumno, $fechaActual); // Vincula los parámetros
        $check_stmt->execute(); // Ejecuta la consulta
        $check_result = $check_stmt->get_result(); // Obtiene el resultado

        if ($check_result->num_rows > 0) { // Si ya existe un registro de asistencia
            // Actualizar la asistencia a "Presente"
            $update_sql = "UPDATE asistencia SET asistencia = 'Presente' WHERE id_alum = ? AND fecha = ?"; // Consulta para actualizar la asistencia
            $update_stmt = $conexion->prepare($update_sql); // Prepara la consulta de actualización
            $update_stmt->bind_param('is', $id_alumno, $fechaActual); // Vincula los parámetros
            
            // Ejecutar la consulta de actualización
            if ($update_stmt->execute()) { 
                // Si se actualiza correctamente
                echo json_encode(['success' => true, 'message' => 'Asistencia actualizada correctamente.']); // Devuelve un mensaje de éxito
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la asistencia.']); // Devuelve un mensaje de error
            }
            $update_stmt->close(); // Cierra la declaración de actualización
        } else {
            // Insertar nuevo registro de asistencia si no existe
            $insert_sql = "INSERT INTO asistencia (id_alum, fecha, asistencia) VALUES (?, ?, 'Presente')"; // Consulta para insertar nueva asistencia
            $insert_stmt = $conexion->prepare($insert_sql); // Prepara la consulta de inserción
            $insert_stmt->bind_param('is', $id_alumno, $fechaActual); // Vincula los parámetros
            
            if ($insert_stmt->execute()) { 
                // Si se inserta correctamente
                echo json_encode(['success' => true, 'message' => 'Asistencia registrada correctamente.']); // Devuelve un mensaje de éxito
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar la asistencia.']); // Devuelve un mensaje de error
            }
            $insert_stmt->close(); // Cierra la declaración de inserción
        }
        $check_stmt->close(); // Cierra la declaración de verificación
    } else {
        echo json_encode(['success' => false, 'message' => 'El código ingresado no es correcto.']); // Mensaje si el código no coincide
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Alumno no encontrado.']); // Mensaje si no se encuentra el alumno
}

$stmt->close(); // Cierra la declaración principal
$conexion->close(); // Cierra la conexión a la base de datos
?>
