<?php

include 'base_de_datos.php'; // Incluye el archivo de conexión a la base de datos
session_start(); // Inicia la sesión

// Verifica si el ID del administrador está en la sesión
if (!isset($_SESSION['admin_id'])) {
    // Si no está, redirige al inicio de sesión
    header("Location: sesion_admi.html");
    exit();
}
$id_ano = isset($_GET['id_ano']) ? intval($_GET['id_ano']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divisiones</title>
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

    <button class="volver-boton" onclick="volver();">Volver</button>

    <h2>Divisiones de  <?php echo $id_ano; ?>° Año</h2>
    <style>
    h2{
        color:white;
        text-align:center;
    }
</style>
    <section class="grid-container">
        <?php
            include 'base_de_datos.php'; // Incluir la conexión a la base de datos

            // Obtener el id_ano y turno de la URL
            $id_ano = isset($_GET['id_ano']) ? intval($_GET['id_ano']) : 0;
            $turno = isset($_GET['turno']) ? $conexion->real_escape_string($_GET['turno']) : '';

            // Consulta para obtener las divisiones según el año y turno seleccionados
            $sql = "SELECT id, Nr, año FROM division WHERE año = $id_ano AND turno = '$turno'";
            $result = $conexion->query($sql);

            if ($result->num_rows > 0) {
                // Salida de cada fila
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="grid-item">';
                    echo '<div class="box" style="background-image: url(imagenes/libreta.png);"></div>'; // Establece la imagen de fondo
                    // Enviar también el turno y el año en la URL
                    echo '<a href="Asistencia_admi.php?id_division=' . $row["id"] . '&turno=' . urlencode($turno) . '&año=' . urlencode($row["año"]) . '">';
                    echo '<button class="btn">' . htmlspecialchars($row["año"]) . '°' . htmlspecialchars($row["Nr"]) . '</button></a>';
                    echo '</div>';
                }
            } else {
                echo "No hay divisiones disponibles para este año y turno.";
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

    <!-- Colocar el script al final del archivo -->
    <script>
        // Codificar turno en PHP para asegurar compatibilidad
        const turno = decodeURIComponent("<?php echo rawurlencode($turno); ?>");

        function volver() {
            window.location.href = `elegir_años.php?turno=${turno}`;
        }
    </script>
</body>
</html>
