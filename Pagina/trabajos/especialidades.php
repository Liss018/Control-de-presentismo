<?php

include 'base_de_datos.php'; 

session_start();


if (!isset($_SESSION['admin_id'])) {
   
    header("Location: sesion_admi.html");
    exit();
}
$id_ano = isset($_GET['id_ano']) ? (int)$_GET['id_ano'] : 0;
$turno = isset($_GET['turno']) ? $_GET['turno'] : ''; // Recibir el turno desde la URL
$sql = "SELECT * FROM especialidades WHERE id_ano = $id_ano";
$result = $conexion->query($sql);

// Array para almacenar especialidades ya mostradas
$especialidades_mostradas = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especialidades del Año <?php echo $id_ano; ?></title>
    <link rel="stylesheet" href="CSS/especialidad.css">
    <link rel="stylesheet" href="CSS/header-footer.css">
</head>
<body>
<header class="header">
        <div class="top-bar">
            <div class="logo">
                <img src="imagenes/logo_Krause.png" alt="Logo">
            </div>
            
            <div class="menu-icon" id="menu-icon">
                &#9776;
            </div>
           
            <nav class="nav" id="nav">
                
                <ul>
                
                <hr>
                    <li class="inicio"><a class="Logout" href="cerrar_sesion_admin.php">CERRAR SESION</a></li>
                <hr>
                </ul>

        </nav>
            <script src="scripts/header.js"></script>
        </div>
        <hr>
    </header>
    <button class="volver-boton" onclick="window.location.href='elegir_años.php?turno=<?php echo urlencode($turno); ?>';">Volver</button>
    <h2>Especialidades de  <?php echo $id_ano; ?>° Año</h2>
    <style>
    h2{
        color:white;
        text-align:center;
    }
</style>
    <section class="grid-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Obtener los valores de la especialidad
                $especialidad = trim(htmlspecialchars($row["tipo"]));
                $base_imagen = 'imagenes/' . strtolower(str_replace(' ', '_', $especialidad)); // Ruta base de la imagen

                // Verificar si la imagen existe en PNG o JPG
                $imagen = '';
                if (file_exists($base_imagen . '.png')) {
                    $imagen = $base_imagen . '.png';
                } elseif (file_exists($base_imagen . '.jpg')) {
                    $imagen = $base_imagen . '.jpg';
                }

                // Verificar si la especialidad ya ha sido mostrada
                if (!in_array($especialidad, $especialidades_mostradas)) {
                    // Añadir la especialidad al array de mostradas
                    $especialidades_mostradas[] = $especialidad;

                    // Construir la URL con especialidad y año
                    // Asegúrate de usar 'id_ano' como clave para el año
                    $url = 'divisiones_especialidad.php?especialidad=' . urlencode($especialidad) . '&turno=' . urlencode($turno) . '&id_ano=' . $id_ano;

                    // Mostrar el botón que lleva a la página de asistencia, pasando la especialidad y año en la URL
                    echo '<div class="grid-item">';
                    if ($imagen) {
                        echo '<img src="' . htmlspecialchars($imagen) . '" alt="' . htmlspecialchars($especialidad) . '" class="especialidad-img">'; // Imagen de la especialidad
                    } else {
                        echo '<p>No hay imagen disponible.</p>'; // Mensaje si no hay imagen
                    }
                    echo '<a href="' . $url . '"><button class="btn">Ingresar</button></a>';
                    echo '</div>';
                }
            }
        } else {
            echo "<p>No hay especialidades disponibles.</p>";
        }
        $conexion->close();
        ?>
    </section>

    <footer>
        <div class="footer-left">
            <img src="imagenes/escudo inferior.png" class="footer-logo">
            <span><a href="https://miok.ottokrause.edu.ar/">MIOK</a></span>
        </div>
        <div class="footer-right">
            <a href="#"><img src="imagenes/twitter.png" class="icon-twitter"></a>
            <a href="#"><img src="imagenes/facebook.png" class="icon-facebook"></a>
        </div>
    </footer>
</body>
</html>
