<?php
include 'base_de_datos.php';
echo "<pre>";
print_r($_POST);
echo "</pre>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

// Obtener los datos del formulario

$nombre = $_POST['Nombre'];
$apellido = $_POST['Apellido'];
$dni = $_POST['Dni'];
$correo = $_POST['Correo'];
$password = $_POST['Contraseña'];
$verificar_password = $_POST['Verificar_Contraseña'];

// Verificar que las contraseñas coincidan
if ($password !== $verificar_password) {
    echo "<script>
            alert('Las contraseñas no coinciden');
            window.location.href = 'registro_admi.html';
          </script>";
    exit();
}

// Verificar que el DNI no esté duplicado
$verificar_dni = mysqli_query($conexion, "SELECT * FROM registro_admi WHERE dni = '$dni'");
if (mysqli_num_rows($verificar_dni) > 0) {
    echo "<script>
            alert('DNI ya existe');
            window.location.href = 'registro_admi.html';
          </script>";
    exit();
}

// Hashear la contraseña antes de almacenarla
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Preparar la consulta
$query = "INSERT INTO registro_admi (nombre, apellido, dni, correo, contraseña) VALUES (?, ?, ?, ?, ?)";

// Verificar si la conexión está bien
if ($stmt = mysqli_prepare($conexion, $query)) {
    
    // Enlazar los parámetros: 'sssis' representa los tipos de los datos (cadena, cadena, entero, cadena, cadena)
    mysqli_stmt_bind_param($stmt, "ssiss", $nombre, $apellido, $dni, $correo, $hashed_password);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Registro exitoso');
            window.location.href = 'sesion_admi.html';
          </script>";
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);
} else {
    echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
}
} else {
    echo "No se han recibido datos del formulario.";
}
// Cerrar la conexión
mysqli_close($conexion);
?>
