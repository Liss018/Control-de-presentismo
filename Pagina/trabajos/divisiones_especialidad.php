<?php
include 'base_de_datos.php'; // Incluye el archivo de conexión a la base de datos
session_start(); // Inicia la sesión

// Verifica si el ID del administrador está en la sesión
if (!isset($_SESSION['admin_id'])) {
    // Si no está, redirige al inicio de sesión
    header("Location: sesion_admi.html");
    exit();
}

// Obtener los valores de la URL
$turno = isset($_GET['turno']) ? htmlspecialchars($_GET['turno']) : ''; // Sanitiza el valor del turno
$id_ano = isset($_GET['id_ano']) ? intval($_GET['id_ano']) : 0; // Convierte el id_ano a entero
$especialidad = isset($_GET['especialidad']) ? htmlspecialchars($_GET['especialidad']) : ''; // Sanitiza el valor de especialidad
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Permite que la página sea responsiva -->
    <title>Divisiones por Especialidad</title>
    <link rel="stylesheet" href="CSS/especialidad.css"> <!-- Vincula la hoja de estilos para la especialidad -->
    <link rel="stylesheet" href="CSS/header-footer.css"> <!-- Vincula la hoja de estilos para el header y footer -->
</head>
<body>
<header class="header">
    <div class="top-bar">
        <div class="logo">
            <img src="imagenes/logo_Krause.png" alt="Logo"> <!-- Logo de la página -->
        </div>
        
        <div class="menu-icon" id="menu-icon">
            &#9776; <!-- Icono de menú -->
        </div>
       
        <nav class="nav" id="nav">
            <ul>
                <hr>
                <li class="inicio"><a class="Logout" href="cerrar_sesion_admin.php">CERRAR SESION</a></li> <!-- Enlace para cerrar sesión -->
                <hr>
            </ul>
        </nav>
        <script src="scripts/header.js"></script> <!-- Vincula el script para la funcionalidad del header -->
    </div>
    <hr>
</header>
<!-- Botón para volver a la página de especialidades -->
<button class="volver-boton" onclick="window.location.href='especialidades.php?turno=<?php echo urlencode($turno); ?>&id_ano=<?php echo $id_ano; ?>&especialidad=<?php echo urlencode($especialidad); ?>';">Volver</button>
<h2>DIVISIONES PARA LA ESPECIALIDAD: <?php echo htmlspecialchars($especialidad); ?></h2> <!-- Título que muestra la especialidad seleccionada -->
<style>
    h2{
        color:white;
        text-align:center;
    }
</style>
<section class="grid-container">
    <?php
        // Genera la consulta SQL
    $sql = "
    SELECT d.id, d.Nr 
    FROM division d 
    JOIN especialidades e ON d.id = e.id_divi 
    WHERE d.turno = '$turno' 
    AND d.año = $id_ano 
    AND e.tipo = '$especialidad'
    ";

    // Imprime la consulta SQL para depuración
    echo "<!-- SQL Query: $sql -->"; // Esto se verá en el código fuente HTML de la página

    $result = $conexion->query($sql); // Ejecuta la consulta

    if ($result->num_rows > 0) { // Verifica si hay resultados
        // Salida de cada fila
        while ($row = $result->fetch_assoc()) { // Itera sobre cada resultado
            echo '<div class="grid-item">';
            echo '<div class="box" style="background-image: url(imagenes/libreta.png);"></div>'; // Establece la imagen de fondo
            // Enlace a la página de asistencia
            echo '<a href="Asistencia_admi_especialidades.php?id_division=' . $row["id"] . '&turno=' . urlencode($turno) . '&id_ano=' . $id_ano . '&especialidad=' . urlencode($especialidad) . '">'; 
            echo '<button class="btn">' . htmlspecialchars($row["Nr"]) . '°</button></a>'; // Botón con el número de división
            echo '</div>';
        }
    } else {
        echo "No hay divisiones disponibles para esta especialidad, turno y año."; // Mensaje si no hay divisiones
    }

    $conexion->close(); // Cerrar conexión
    ?>
</section>

<footer>
    <div class="footer-left">
        <img src="imagenes/escudo inferior.png" class="footer-logo"> <!-- Logo del footer -->
        <span><a href="https://miok.ottokrause.edu.ar/">MIOK</a></span> <!-- Enlace al sitio MIOK -->
    </div>
    <div class="footer-right">
        <a href="#"><img src="imagenes/twitter.png" class="icon-twitter"></a> <!-- Icono de Twitter -->
        <a href="#"><img src="imagenes/facebook.png" class="icon-facebook"></a> <!-- Icono de Facebook -->
    </div>
</footer>
</body>
</html>
