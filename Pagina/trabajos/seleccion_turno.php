<?php
session_start(); // Inicia la sesión

// Verifica si el ID del administrador está en la sesión
if (!isset($_SESSION['admin_id'])) {
    // Si no está, redirige al inicio de sesión
    header("Location: sesion_admi.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="CSS/seleccion.css">
    <link rel="stylesheet" href="CSS/header-footer.css">
    <script>
        // Función para redirigir a la página de años según el turno
        function irAPagina(turno) {
            window.location.href = 'elegir_años.php?turno=' + turno;
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <section class="grid-container">
        <div class="grid-item">
            <div class="box"></div>
            <button class="btn" onclick="irAPagina('mañana')">Turno Mañana</button>
        </div>
        <div class="grid-item">
            <div class="box"></div>
            <button class="btn" onclick="irAPagina('tarde')">Turno Tarde</button>
        </div>
    </section>
</body>
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
</html>
