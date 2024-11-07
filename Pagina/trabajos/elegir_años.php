<?php

session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elija Año</title>
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
    <button class="volver-boton" onclick="window.location.href='seleccion_turno.php';">Volver</button>

    <h1>Años</h1>
    <section class="grid-container">
        
        <?php
        include 'base_de_datos.php'; // Incluir la conexión a la base de datos

        // Obtener el turno desde la URL
        $turno = isset($_GET['turno']) ? $_GET['turno'] : '';

        // Consulta para obtener los años
        $sql = "SELECT id_ano, descripcion FROM año"; // Ajusta según tu estructura
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            // Salida de cada fila
            while ($row = $result->fetch_assoc()) {
                echo '<div class="grid-item">';
                echo '<div class="box" style="background-image: url(imagenes/libreta.png);"></div>';

                // Verifica el id_ano para determinar el enlace
                if ($row["id_ano"] == 1 || $row["id_ano"] == 2) {
                    // Enlace a divisiones
                    echo '<a href="divisiones.php?id_ano=' . $row["id_ano"] . '&turno=' . urlencode($turno) . '">';
                    echo '<button class="btn">Ingresar a ' . htmlspecialchars($row["descripcion"]) . '</button></a>';
                } elseif ($row["id_ano"] >= 3) {
                    // Enlace a especialidades
                    echo '<a href="especialidades.php?id_ano=' . $row["id_ano"] . '&turno=' . urlencode($turno) . '">';
                    echo '<button class="btn">Ingresar a ' . htmlspecialchars($row["descripcion"]) . '</button></a>';
                }

                echo '</div>';
            }
        } else {
            echo "No hay años disponibles.";
        }

        $conexion->close(); // Cerrar conexión
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
