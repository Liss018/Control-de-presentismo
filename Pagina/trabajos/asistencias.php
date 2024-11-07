<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión

// Verificar si el alumno está autenticado
if (!isset($_SESSION['alumno_id'])) { 
    // Si no está autenticado, redirige a la página de inicio de sesión
    header("Location: index.php");
    exit();
}

include 'base_de_datos.php'; // Incluye el archivo de conexión a la base de datos

$alumno_id = $_SESSION['alumno_id']; // Obtiene el id del alumno desde la sesión

// Consulta para obtener las asistencias del alumno usando el id_alum
$query = "SELECT fecha, asistencia, observaciones FROM asistencia WHERE id_alum = ? ORDER BY fecha DESC";
$stmt = mysqli_prepare($conexion, $query); // Prepara la consulta SQL
mysqli_stmt_bind_param($stmt, "i", $alumno_id); // Vincula el parámetro del id del alumno
mysqli_stmt_execute($stmt); // Ejecuta la consulta
mysqli_stmt_bind_result($stmt, $fecha_asistencia, $estado_asistencia, $observaciones_asistencia); // Vincula los resultados a variables

$asistencias = []; // Array para almacenar los registros de asistencia
while (mysqli_stmt_fetch($stmt)) { // Recorre los resultados
    $asistencias[] = [
        'fecha' => $fecha_asistencia,
        'estado' => $estado_asistencia,
        'observaciones' => $observaciones_asistencia,
    ]; // Agrega cada registro de asistencia al array
}
mysqli_stmt_close($stmt); // Cierra la declaración preparada

// Cierra la conexión a la base de datos
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias del Alumno</title>
    
    <link rel="stylesheet" href="CSS/sesion_alum.css"> <!-- Archivo CSS para estilos de la sesión del alumno -->
    <link rel="stylesheet" href="CSS/header-footer.css"> <!-- Archivo CSS para el header y footer -->
</head>
<body>
<header class="header">
        <div class="top-bar">
            <div class="logo">
                <img src="imagenes/logo_Krause.png" alt="Logo"> <!-- Logo de la institución -->
            </div>
            
            <div class="menu-icon" id="menu-icon">
                &#9776;
            </div>
           
            <nav class="nav" id="nav">                
                <ul><hr>
                    <li><a href="sesion_alumno.php">INICIO</a></li> <!-- Enlace al inicio del alumno -->
                    <hr>
                    <li><a href="asistencias.php">ASISTENCIAS</a></li> <!-- Enlace a la página de asistencias -->
                    <hr>
                    <li class="inicio"><a class="Logout" href="cerrar_sesion_admin.php">CERRAR SESION</a></li> <!-- Enlace para cerrar sesión -->
                    <hr>
                </ul>
            </nav>
            <script src="scripts/header.js"></script> <!-- Script para la funcionalidad del header -->
        </div>
        <hr>
</header>

<div class="container">
    <h2>Mis Asistencias</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($asistencias)): ?> <!-- Verifica si no hay registros de asistencia -->
                <tr>
                    <td colspan="3">No hay registros de asistencia.</td> <!-- Mensaje si no hay asistencias -->
                </tr>
            <?php else: ?>
                <?php foreach ($asistencias as $asistencia): ?> <!-- Recorre los registros de asistencia -->
                    <tr>
                        <td><?php echo htmlspecialchars($asistencia['fecha']); ?></td> <!-- Fecha de asistencia -->
                        <td><?php echo htmlspecialchars($asistencia['estado']); ?></td> <!-- Estado de asistencia -->
                        <td><?php echo htmlspecialchars($asistencia['observaciones'] ?? 'Sin observaciones'); ?></td> <!-- Observaciones, si existen -->
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    tbody , thead{
        color:white;
    }
</style>

<footer>
    <div class="footer-left">
        <img src="imagenes/escudo_inferior.png" class="footer-logo"> <!-- Imagen del escudo en el footer -->
        <span><a href="https://miok.ottokrause.edu.ar/">MIOK</a></span>
    </div>
    <div class="footer-right">
        <a href="#"><img src="imagenes/twitter.png" class="icon-twitter"></a> <!-- Icono de Twitter -->
        <a href="#"><img src="imagenes/facebook.png" class="icon-facebook"></a> <!-- Icono de Facebook -->
    </div>
</footer>

</body>
</html>

<style>
    /* Estilos para la tabla */
    .container{
        margin:0px;
    }
    table {
        width: 70%;
        margin: 0 auto;
        margin-top:40px;
        border-collapse: collapse;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3); /* Sombra en la tabla */
        background-color: #333;
    }

    thead th {
        background-color: #8b0a0a;
        color: white;
        padding: 10px;
        font-weight: bold;
        text-transform: uppercase; /* Texto en mayúsculas */
    }

    tbody td {
        background-color: #555;
        color: #fff;
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #444; /* Línea divisoria en la tabla */
    }

    tbody tr:hover {
        background-color: #777; /* Cambia el color al pasar el ratón */
    }

    th, td {
        border-right: 1px solid #444;
    }

    th:last-child, td:last-child {
        border-right: none;
    }

    td:empty::before {
        content: '—'; /* Muestra un guión si la celda está vacía */
        color: #ccc;
    }

    tbody tr td[colspan="3"] {
        font-style: italic;
        color: #bbb;
        text-align: center;
        padding: 15px;
    }

    @media (max-width: 600px) {
        .container{
            padding:0px;
        }
        .table{
            font-size:10px; /* Ajusta el tamaño de fuente en pantallas pequeñas */
        }
    }
</style>
