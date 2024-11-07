<?php
session_start(); // Iniciar la sesión
include 'base_de_datos.php';

// Función para generar un código aleatorio de 16 caracteres
function generarCodigoAleatorio($longitud = 16) {
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $codigo = '';
    
    for ($i = 0; $i < $longitud; $i++) {
        $indiceAleatorio = rand(0, strlen($caracteres) - 1); // Genera un índice aleatorio
        $codigo .= $caracteres[$indiceAleatorio]; // Agrega el carácter al código
    }
    
    return $codigo;
}

// Función para verificar si el código ya existe
function codigoExiste($conexion, $codigo) {
    $query = "SELECT * FROM registro_alum WHERE codigo = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $codigo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($resultado) > 0; // Retorna true si existe
}

// Obtener los datos del formulario
$dni = trim($_POST['dni']); // Usar trim para eliminar espacios
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$telefono = trim($_POST['telefono']);
$id_ano = $_POST['anio'];
$nr_divi = $_POST['division']; 

$verificar_dni = mysqli_query($conexion, "SELECT * FROM registro_alum WHERE dni = '$dni'"); // Verificar si el DNI ya existe en la base de datos
if (mysqli_num_rows($verificar_dni) > 0) {
    $alumno_existente = mysqli_fetch_assoc($verificar_dni); // Obtener los datos del alumno existente

    // Comparar los datos ingresados con los datos existentes
    if (
        strtolower(trim($alumno_existente['nombre'])) === strtolower(trim($nombre)) &&
        strtolower(trim($alumno_existente['apellido'])) === strtolower(trim($apellido)) &&
        trim($alumno_existente['telefono']) === trim($telefono) &&
        (int)$alumno_existente['id_ano'] === (int)$id_ano &&
        (int)$alumno_existente['id_divi'] === (int)$nr_divi
    ) {
        // Los datos son iguales, redirigir a otra página
        $_SESSION['alumno_id'] = $alumno_existente['id_alum'];
        echo "<script>
                window.location.href = 'sesion_alumno.php';
              </script>";
        exit();
    } else {
        // Datos del alumno incorrectos
        echo "Datos incorrectos.";
        exit();
    }
}

// Si no hay especialidad seleccionada, asignar NULL
$id_espe = isset($_POST['especialidad']) && $_POST['especialidad'] !== '' ? $_POST['especialidad'] : NULL;

// Obtener el turno y el id_divi de la división
$query_division = "SELECT id, turno FROM division WHERE Nr = ? AND año = ?";
$stmt_division = mysqli_prepare($conexion, $query_division);
mysqli_stmt_bind_param($stmt_division, "ii", $nr_divi, $id_ano);
mysqli_stmt_execute($stmt_division);
mysqli_stmt_bind_result($stmt_division, $id_divi, $turno);
mysqli_stmt_fetch($stmt_division);
mysqli_stmt_close($stmt_division);

// Generar un código único
do {
    $codigo = generarCodigoAleatorio(); // Generar código de verificación aleatorio
} while (codigoExiste($conexion, $codigo)); // Verificar si el código ya existe

// Preparar la consulta para insertar el registro del alumno
$query = "INSERT INTO registro_alum (dni, nombre, apellido, telefono, id_ano, id_divi, id_espe, codigo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Verificar si la conexión está bien
if ($stmt = mysqli_prepare($conexion, $query)) {
    // Enlazar los parámetros
    mysqli_stmt_bind_param($stmt, "issiiiis", $dni, $nombre, $apellido, $telefono, $id_ano, $id_divi, $id_espe, $codigo);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Almacenar el ID del nuevo alumno en la sesión
        $_SESSION['alumno_id'] = mysqli_insert_id($conexion); // Obtener el ID del último registro insertado

        // Insertar en la tabla de asistencia
        $id_alumno = $_SESSION['alumno_id'];
        $fecha_actual = date('Y-m-d'); // Obtener la fecha actual

        // Verificar si ya existe una entrada de asistencia para el día actual
        $query_asistencia = "SELECT * FROM asistencia WHERE id_alum = ? AND fecha = ?";
        $stmt_asistencia = mysqli_prepare($conexion, $query_asistencia);
        mysqli_stmt_bind_param($stmt_asistencia, "is", $id_alumno, $fecha_actual);
        mysqli_stmt_execute($stmt_asistencia);
        $resultado_asistencia = mysqli_stmt_get_result($stmt_asistencia);

        if (mysqli_num_rows($resultado_asistencia) == 0) { // Si no existe una entrada para el día
            // Insertar asistencia como ausente
            $query_insert_asistencia = "INSERT INTO asistencia (id_alum, fecha, asistencia) VALUES (?, ?, 'ausente')";
            $stmt_insert_asistencia = mysqli_prepare($conexion, $query_insert_asistencia);
            mysqli_stmt_bind_param($stmt_insert_asistencia, "is", $id_alumno, $fecha_actual);
            mysqli_stmt_execute($stmt_insert_asistencia);
            mysqli_stmt_close($stmt_insert_asistencia);
        }

        echo "<script>
            window.location.href = 'registro_completo_alumno.html';
          </script>";
        exit(); // Asegúrate de que el script se detenga después
    } else {
        echo "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt);
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);
} else {
    echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
}

// Cerrar la conexión
mysqli_close($conexion);
?>
