<?php
$ipPermitida = '::1';  // Reemplaza con la IP pública de la red de la escuela

// Obtener IP del visitante
$ipUsuario = $_SERVER['REMOTE_ADDR'];

if ($ipUsuario !== $ipPermitida) {
    // Si no está en la red de la escuela, denegar el acceso
    header("Location: acceso_denegado.php");
    exit();
}

// Código de la aplicación continúa aquí
?>



<?php
session_start(); // Iniciar la sesión

// Verificar si el alumno está autenticado
if (!isset($_SESSION['alumno_id'])) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header("Location: registro_alumno.php");
    exit();
}

include 'base_de_datos.php';

$alumno_id = $_SESSION['alumno_id'];

// Consulta para obtener el código único, nombre y apellido del alumno
$query = "SELECT codigo, nombre, apellido FROM registro_alum WHERE id_alum = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $alumno_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $codigo_unico, $nombre_alumno, $apellido_alumno);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Cerrar la conexión
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Presente</title>
    
    <link rel="stylesheet" href="CSS/sesion_alum.css">
    <link rel="stylesheet" href="CSS/header-footer.css">
</head>
<body>
<header class="header">
        <div class="top-bar">
            <div class="logo">
                <img src="imagenes/logo_krause.png" alt="Logo">
            </div>
            
            <div class="menu-icon" id="menu-icon">
                &#9776;
            </div>
           
            <nav class="nav" id="nav">
                
                <ul><hr>
                    <li><a href="sesion_alumno.php">INICIO</a></li>
                <hr>
                <li><a href="asistencias.php">ASISTENCIAS</a></li>
                <hr>
                    <li class="inicio"><a class="Logout" href="cerrar_sesion_admin.php">CERRAR SESION</a></li>
                <hr>
                </ul>

        </nav>
            <script src="scripts/header.js"></script>
        </div>
        <hr>
    </header>



    <div class="container">
        <!-- Mostrar el nombre y apellido del alumno -->
        <h2><?php echo htmlspecialchars($nombre_alumno . ' ' . $apellido_alumno); ?></h2>
        
        <div class="codigo-alumno">
            <label for="codigo">Código único:</label>
            <div>
                <!-- Mostrar el código único obtenido de la sesión -->
                <input type="text" id="codigo" value="<?php echo htmlspecialchars($codigo_unico); ?>" readonly>
                <button onclick="copiarCodigo()">Copiar Código</button>
                <div id="mensaje" style="display: none; color: green;">Código copiado al portapapeles</div>
            </div>
        </div>
        <button onclick="mostrarQR()" style="background-color: red;">Mostrar QR</button>
    </div>

    <!-- Ventana emergente para mostrar el QR -->
    <div id="modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3>Código QR</h3>
            <img id="qrCode" src="" alt="Código QR">
        </div>
    </div>

    <script src="scripts/script.js"></script>

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
