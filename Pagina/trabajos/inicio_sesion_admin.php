<?php
include 'base_de_datos.php'; // Asegúrate de que este archivo tenga la conexión correcta

session_start(); // Inicia la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe datos del formulario
    $dni = $_POST['dni'];
    $password = $_POST['password'];

    // Consulta para verificar el administrador
    $sql = "SELECT * FROM registro_admi WHERE dni = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $dni); // Vincula el parámetro
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica si se encontraron resultados
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            // Verifica la contraseña
            if (password_verify($password, $admin['contraseña'])) {
                // Credenciales correctas
                $_SESSION['admin_id'] = $admin['id_admi']; // Almacena el ID en la sesión
                echo "<script>alert('Bienvenido, " . htmlspecialchars($admin['nombre']) . " " . htmlspecialchars($admin['apellido']) . "!')</script>";
                // Redirigir a la página de administración o panel principal
                header("Location: seleccion_turno.php");
                exit();
            } else {
                // Contraseña incorrecta
                echo "<script>alert('DNI o contraseña incorrectos.'); </script>";
            }
        } else {
            // DNI no encontrado
            echo "<script>alert('DNI o contraseña incorrectos.'); </script>";
        }

        // Cierra la declaración después de usarla
        $stmt->close();
    } else {
        echo "Error al preparar la declaración: " . $conexion->error;
    }
}
// Cierra la conexión
$conexion->close();
?>
