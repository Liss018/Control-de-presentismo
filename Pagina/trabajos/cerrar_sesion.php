<?php
session_start(); // Iniciar la sesión

// Verificar si el ID de alumno está en la sesión y eliminarlo
if (isset($_SESSION['alumno_id'])) {
    unset($_SESSION['alumno_id']); // Eliminar el ID del alumno de la sesión
}   

// Destruir la sesión por completo (opcional)
session_destroy(); // Destruir la sesión actual

// Redireccionar a la página de inicio o de login
header("Location: registro_alumno.php"); 
exit();
?>
